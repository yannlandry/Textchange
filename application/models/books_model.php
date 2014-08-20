<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Books_model extends CI_Model {
/* Profils, membres, etc. */



	const PICTURES_PATH = 'uploads/books';
	
	
	
	# Récupère les annonces les plus récentes
	public function get_recent_ads()
	{
		$cachefile = 'application/cache/home/recent.tmp';
		if(file_exists($cachefile) && time() - filemtime($cachefile) < 60)
			return unserialize(file_get_contents($cachefile));
		
		$Q = $this->db->query("SELECT AdID, Title, ISBN, Picture, Price FROM ads ORDER BY AdID DESC LIMIT 0,12")->result();
		file_put_contents($cachefile, serialize($Q));
		
		return $Q;
	}
	
	
	
	# Récupère la liste des livres pour l'accueil
	public function get_home_showcase($searchLang)
	{
		$cachefile = 'application/cache/books/home/'.User::lang().'_showcase_'.$searchLang.'.tmp';
		if(file_exists($cachefile) && time() - filemtime($cachefile) < 300)
			return unserialize(file_get_contents($cachefile));
		
		$lang = $searchLang != 'all' ? "WHERE A.Language = '$searchLang'" : "";
		
		$Q = $this->db->query("SELECT A.DomainID, COUNT(DomainID) AS AdCount, (SELECT ".ucfirst(User::lang())."Name FROM domains WHERE DomainID = A.DomainID LIMIT 1) AS DomainName FROM ads AS A $lang GROUP BY A.DomainID HAVING AdCount > 0 ORDER BY AdCount DESC LIMIT 0,4")->result();
		
		if(empty($Q)) return $Q;
		
		$D = array();
		$QElems = array();
		foreach($Q as $dom) {
			$D[$dom->DomainID] = $dom;
			$D[$dom->DomainID]->Ads = array();
			$QElems[] = "SELECT AdID, DomainID, Title, ISBN, Picture, Price FROM ads WHERE DomainID = ".intval($dom->DomainID)." ORDER BY AdID DESC LIMIT 0,6";
		}
		unset($Q, $dom);
		
		$A = $this->db->query("(".implode(") UNION ALL (", $QElems).")")->result();
		
		foreach($A as $ad)
			$D[$ad->DomainID]->Ads[] = $ad;
		unset($A);
		
		file_put_contents($cachefile, serialize($D));
		
		return $D;
	}
	
	
	
	# Récupère la liste des domaines des livres
	public function get_domains()
	{
		$cachefile = 'application/cache/books/domains/list_'.User::lang().'.tmp';
		if(file_exists($cachefile) && time() - filemtime($cachefile) < 300)
			return unserialize(file_get_contents($cachefile));
		
		$master = array('left' => array(), 'right' => array());
		$list = $this->db->query("SELECT DomainID, ".ucfirst(User::lang())."Name AS DomainName FROM domains ORDER BY DomainName DESC")->result();
		
		for($i = 0; count($list) > 0; $i++) {
			$obj = array_pop($list);
			$master[$i % 2 == 0 ? 'left' : 'right'][$obj->DomainID] = $obj->DomainName;
		}
		
		file_put_contents($cachefile, serialize($master));
		
		return $master;
	}
	
	
	
	# Récupère les informations sur un domaine
	public function get_domain_data($DomainID)
	{
		if($DomainID == 0) return FALSE;
		
		$Q = $this->db->query("SELECT DomainID, ".ucfirst(User::lang())."Name AS DomainName FROM domains WHERE DomainID = ? LIMIT 1", array($DomainID));
		
		if($Q->num_rows() == 0) return FALSE;
		else return $Q->row();
	}
	
	
	
	# Récupère les livres pour un domaine
	public function get_ads_from_domain($DomainID, $start, $length, $searchLang, $searchSort, $searchOrder)
	{
		++$length;
		
		$lang = $searchLang != 'all' ? "AND Language = '$searchLang'" : "";
		$sort = $searchSort == 'date' ? "AdID" : "Price";
		$order = $searchOrder;
		
		return $this->db->query("SELECT AdID, Title, ISBN, Picture, Price FROM ads WHERE DomainID = ? $lang ORDER BY $sort $order LIMIT 0,24", array($DomainID))->result();
	}
	
	
	
	
	# Récupère les livres avec le même ISBN
	public function get_similar_isbn($ISBN, $start, $length, $exclude = 0)
	{
		return $this->db->query("SELECT AdID, Title, ISBN, Picture, Price FROM ads WHERE ISBN = ? AND AdID != ? ORDER BY AdID DESC LIMIT ?,?", array($ISBN, $exclude, $start, $length))->result();
	}
	
	
	
	
	# Récupère les livres avec un ISBN
	public function get_ads_with_isbn($ISBN, $start, $length, $searchSort, $searchOrder)
	{
		if(!valid_isbn($ISBN)) return NULL;
		if(strlen($ISBN) == 10) $ISBN = convert_isbn($ISBN);
		
		++$length;
		
		$sort = $searchSort == 'date' ? "AdID" : "Price";
		$order = $searchOrder;
		
		return $this->db->query("SELECT AdID, Title, ISBN, Picture, Price FROM ads WHERE ISBN = ? ORDER BY $sort $order LIMIT ?,?", array($ISBN, $start, $length))->result();
	}
	
	
	
	
	# Récupère les autres livres d'un utilisateur
	public function get_user_ads($UserID, $start, $length)
	{
		return $this->db->query("SELECT AdID, Title, ISBN, Picture, Price FROM ads WHERE UserID = ? ORDER BY AdID DESC LIMIT ?,?", array($UserID, $start, $length))->result();
	}
	
	
	
	# Récupère les informations d'un utilisateur
	public function get_user_data($username)
	{
		if(empty($username)) return FALSE;
		
		$Q = $this->db->query("SELECT UserID, Username, RealName, Picture FROM users WHERE Username = ? LIMIT 1", array($username));
		
		if($Q->num_rows() == 0) return false;
		else return $Q->row();
	}
	
	
	
	# Récupère tous les livres d'un utilisateur
	public function get_all_user_ads($UserID, $start, $length, $searchLang, $searchSort, $searchOrder)
	{
		++$length;
		
		$lang = $searchLang != 'all' ? "AND Language = '$searchLang'" : "";
		$sort = $searchSort == 'date' ? "AdID" : "Price";
		$order = $searchOrder;
		
		return $this->db->query("SELECT AdID, Title, ISBN, Picture, Price FROM ads WHERE UserID = ? $lang ORDER BY $sort $order LIMIT ?,?", array($UserID, $start, $length))->result();
	}
	
	
	
	# Retourne une langue de recherche, en règle une si aucune n'existe
	public function get_searchlang()
	{
		if(in_array($this->input->get('srclang'), array('all', 'french', 'english', 'other')))
			return User::searchprefs('lang', $this->input->get('srclang'));
			
		elseif(User::searchprefs('lang') !== FALSE)
			return User::searchprefs('lang');
		
		else
			return User::searchprefs('lang', User::lang());
	}
	
	
	
	# Retourne un tri pour les résultats, en règle un si aucun n'existe
	public function get_searchsort()
	{
		if(in_array($this->input->get('srcsort'), array('date', 'price')))
			return User::searchprefs('sort', $this->input->get('srcsort'));
			
		elseif(User::searchprefs('sort') !== FALSE)
			return User::searchprefs('sort');
		
		else
			return User::searchprefs('sort', 'price');
	}
	
	
	
	# Retourne un ordre pour les résultats, en règle un si aucun n'existe
	public function get_searchorder()
	{
		if(in_array($this->input->get('srcorder'), array('asc', 'desc')))
			return User::searchprefs('order', $this->input->get('srcorder'));
			
		elseif(User::searchprefs('order') !== FALSE)
			return User::searchprefs('order');
		
		else
			return User::searchprefs('order', 'asc');
	}
	
	
	
	# Retourne les matières sélectionnées
	public function get_searchsubjects()
	{
		if(is_array($this->input->post('srcsubjects'))) {
			$searchSubjects = $this->input->post('srcsubjects');
			array_walk($searchSubjects, 'intval');
			return User::searchprefs('subjects', $searchSubjects);
		}
		
		elseif(User::searchprefs('subjects') !== FALSE)
			return User::searchprefs('subjects');
		
		else
			return User::searchprefs('subjects', array());
	}
	
	
	
	# Vue d'une offre
	public function get_ad($AdID)
	{
		if($AdID == 0) return FALSE;
		
		$Q = $this->db->query("
			SELECT
				A.AdID,
				A.UserID,
				A.DomainID,
				A.ISBN,
				A.Title,
				A.Authors,
				A.Publisher,
				A.PubYear,
				A.Language,
				A.Information,
				A.Picture,
				A.Price,
				A.Creation,
				U.Username,
				U.RealName,
				U.Picture AS UserPicture,
				U.UnregContact,
				(SELECT ".ucfirst(User::lang())."Name FROM domains WHERE DomainID = A.DomainID LIMIT 1) AS DomainName,
				(SELECT 1 FROM alerts WHERE UserID = ? AND ISBN = A.ISBN) AS IsWatched
			FROM
				(SELECT
					AdID,
					UserID,
					DomainID,
					ISBN,
					Title,
					Authors,
					Publisher,
					PubYear,
					Language,
					Information,
					Picture,
					Price,
					Creation
				FROM ads 
				WHERE AdID = ?
				LIMIT 1) AS A
			INNER JOIN users AS U
				ON U.UserID = A.UserID
		", array(User::id(), $AdID));
		
		if($Q->num_rows() == 0) return FALSE;
		else return $Q->row();
	}
	
	
	
	# Édition d'une offre
	public function get_ad_edit($AdID)
	{
		if($AdID == 0) return FALSE;
		
		$Q = $this->db->query("SELECT AdID, UserID, DomainID, ISBN, Title, Authors, Publisher, PubYear, Language, Information, Picture, Price, (SELECT ".ucfirst(User::lang())."Name FROM domains WHERE DomainID = A.DomainID LIMIT 1) AS DomainName FROM ads AS A WHERE AdID = ? LIMIT 1", array($AdID));
		
		if($Q->num_rows() == 0) return FALSE;
		else return $Q->row();
	}
	
	
	
	# Suppression d'une offre
	public function get_ad_delete($AdID)
	{
		if($AdID == 0) return FALSE;
		
		$Q = $this->db->query("SELECT AdID, UserID, Title, DomainID, (SELECT ".ucfirst(User::lang())."Name FROM domains WHERE DomainID = A.DomainID LIMIT 1) AS DomainName FROM ads AS A WHERE AdID = ? LIMIT 1", array($AdID));
		
		if($Q->num_rows() == 0) return FALSE;
		else return $Q->row();
	}
	
	
	
	# Contacter un vendeur par courriel
	public function get_ad_email($AdID)
	{
		if($AdID == 0) return FALSE;
		
		$Q = $this->db->query("SELECT A.AdID, A.Title, A.DomainID, (SELECT ".ucfirst(User::lang())."Name FROM domains WHERE DomainID = A.DomainID LIMIT 1) AS DomainName, U.Language AS UserLanguage, U.Email, U.UnregContact FROM (SELECT AdID, UserID, Title, DomainID FROM ads WHERE AdID = ? LIMIT 1) AS A INNER JOIN users AS U ON U.UserID = A.UserID", array($AdID));
		
		if($Q->num_rows() == 0) return FALSE;
		else return $Q->row();
	}
	
	
	
	# Signalement d'une offre
	public function get_ad_report($AdID)
	{
		if($AdID == 0) return FALSE;
		
		$Q = $this->db->query("SELECT AdID, Title, DomainID, (SELECT ".ucfirst(User::lang())."Name FROM domains WHERE DomainID = A.DomainID LIMIT 1) AS DomainName FROM ads AS A WHERE AdID = ? LIMIT 1", array($AdID));
		
		if($Q->num_rows() == 0) return FALSE;
		else return $Q->row();
	}
	
	
	
	# Récupérer les domaines
	public function get_possible_domains()
	{
		$domains = $this->db->query("SELECT DomainID, ".ucfirst(User::lang())."Name AS DomainName FROM domains ORDER BY DomainName ASC")->result();
		
		$R = array();
		foreach($domains as $D)
			$R[$D->DomainID] = $D->DomainName;
		
		return $R;
	}
	
	
	
	# Création d'une offre
	public function create_ad()
	{
		$data = array(
			'UserID' => User::id(),
			'DomainID' => $this->input->post('domain'),
			'ISBN' => convert_isbn($this->input->post('isbn')),
			'Title' => $this->input->post('title'),
			'Authors' => $this->input->post('authors'),
			'Publisher' => $this->input->post('publisher'),
			'PubYear' => $this->input->post('pubyear'),
			'Language' => $this->input->post('lang'),
			'Information' => $this->input->post('information'),
			'Price' => $this->input->post('price')
		);
	
		$this->db->insert('ads', $data);
		$insertID = $this->db->insert_id();
		
		# Envoyons l'image, s'il y en a une
		if($this->input->post('picture-actions') == 'use') {
			$pic = $this->upload_book_picture($insertID);
			if($pic !== FALSE) {
				$this->db->where('AdID', $insertID);
				$this->db->update('ads', array('Picture' => $pic));
			}
		}
		else
			$pic = NULL;
		
		return array($insertID, $pic);
	}
	
	
	
	# Mise à jour de l'offre
	public function update_ad($AdID)
	{
		$data = array(
			'DomainID' => $this->input->post('domain'),
			'ISBN' => convert_isbn($this->input->post('isbn')),
			'Title' => $this->input->post('title'),
			'Authors' => $this->input->post('authors'),
			'Publisher' => $this->input->post('publisher'),
			'PubYear' => $this->input->post('pubyear'),
			'Language' => $this->input->post('lang'),
			'Information' => $this->input->post('information'),
			'Price' => $this->input->post('price')
		);
		
		if($this->input->post('picture-actions') == 'delete')
			$data['Picture'] = "";
		elseif($this->input->post('picture-actions') == 'use') {
			$pic = $this->upload_book_picture($AdID);
			if($pic !== FALSE)
				$data['Picture'] = $pic;
		}
		
		$this->db->where('AdID', $AdID);
		$this->db->update('ads', $data);
		
		return $this->db->affected_rows();
	}
	
	
	
	# Suppression d'une offre
	public function delete_ad($AdID)
	{
		$this->db->where('AdID', $AdID);
		$this->db->delete('ads');
		
		return $this->db->affected_rows();
	}
	
	
	
	# Signalement d'une offre
	public function report_ad($AdID)
	{
		$data = array(
			'AdID' => $AdID,
			'ByUserID' => User::is_connected() ? User::id() : 0,
			'Reason' => $this->input->post('reason')
		);
		
		$this->db->insert('ads_reports', $data);
		
		return $this->db->insert_id();
	}
	
	
	
	# Envoi d'une image pour un livre
	public function upload_book_picture($AdID)
	{
		if(!isset($_FILES['new-picture'])) return FALSE;
		
		if($_FILES['new-picture']['error'] != 0) {
			addFlash(lang('editad_uploaderror_flash'), 'error');
			return FALSE;
		}
		
		if($_FILES['new-picture']['size'] > 6291456) { # Taille > 6 Mo
			addFlash(lang('editad_toolarge_flash'), 'error');
			return FALSE;
		}
		
		if(!is_image($_FILES['new-picture']['tmp_name'])) {
			addFlash(lang('editad_notimage_flash'), 'error');
			return FALSE;
		}
		
		$F =& $_FILES['new-picture'];
		$savename = $AdID.'_'.time();
		list($w, $h) = getimagesize($F['tmp_name']);
		$ext = get_file_ext($F['name']);
		
		$I = create_image_from_file($F['tmp_name'], $ext);
		if($w > 450 || $h > 600) $I = resize_image($I, 450, 600, false);
		save_image_to_file($I, 'uploads/books/'.$savename, $ext, 75);
		
		return $savename.'.'.$ext;
	}
	
	
	
	# Fonction de recherche
	public function search($query, $start, $length, $searchSubjects, $searchLang, $searchSort, $searchOrder)
	{
		++$length;
		
		$query = trim($query);
		$lang = $searchLang != 'all' ? "AND Language = '$searchLang'" : "";
		$sort = $searchSort == 'date' ? "AdID" : "Price";
		$order = $searchOrder;
		$subjects = !empty($searchSubjects) ? implode(",", $searchSubjects) : "";
		
		# ID de recherche
		$searchID = $query.';'.$lang;
		if(!empty($subjects)) $searchID.= ";".$subjects;
		$searchID = md5($searchID);
		
		# Fichier de cache
		$cachefile = 'application/cache/search/ads/'.User::lang().'/'.$searchID.'.tmp';
		
		# Cache inexistant ou trop ancien
		if( !(file_exists($cachefile) && time() - filemtime($cachefile) < 900) ) {
			$RES = $this->db->query("SELECT A.AdID, A.UserID, U.Username, U.RealName, A.DomainID, (SELECT ".ucfirst(User::lang())."Name FROM domains WHERE DomainID = A.DomainID LIMIT 1) AS DomainName, A.ISBN, A.Title, A.Authors, A.Picture, A.Price, A.Creation FROM (SELECT AdID, UserID, DomainID, ISBN, Title, Authors, Picture, Price, Creation FROM ads WHERE MATCH(Title) AGAINST(?) $lang) AS A INNER JOIN users AS U ON U.UserID = A.UserID ORDER BY $sort $order", array($query))->result();
			$RES[] = (object)array('sort' => $searchSort, 'order' => $searchOrder);
			file_put_contents($cachefile, serialize($RES));
			array_pop($RES);
			return array_slice($RES, $start, $length);
		}
		
		# Cache valide
		$RES = unserialize(file_get_contents($cachefile));
		$params = array_pop($RES);
		
		if($params->sort == $searchSort && $params->order == $searchOrder) # Cache ordonné correctement
			return array_slice($RES, $start, $length);
		
		elseif($params->sort == $searchSort) { # Cache dans le mauvais sens
			$RES = array_reverse($RES);
			$RES[] = (object)array('sort' => $searchSort, 'order' => $searchOrder);
			file_put_contents($cachefile, serialize($RES));
			array_pop($RES);
			return array_slice($RES, $start, $length);
		}
		
		else { # Cache trié incorrectement
			if($searchSort == 'price') usort($RES, function(&$A, &$B){
				return $A->Price - $B->Price;
			});
			elseif($searchSort == 'date') usort($RES, function(&$A, &$B){
				return strtotime($A->Creation) - $B->Creation;
			});
			
			if($searchOrder == 'desc') $RES = array_reverse($RES);
			
			$RES[] = (object)array('sort' => $searchSort, 'order' => $searchOrder);
			file_put_contents($cachefile, serialize($RES));
			array_pop($RES);
			return array_slice($RES, $start, $length);
		}
	}
	
	
	
	# Surveille un ISBN
	public function watch_isbn($ISBN = '', $Title = '')
	{
		if(!valid_isbn($ISBN)) return FALSE;
		if(strlen($ISBN) == 10) $ISBN = convert_isbn($ISBN);
		
		$this->db->query("REPLACE INTO alerts (UserID, ISBN, BookTitle) VALUES (?, ?, ?) ", array(User::id(), $ISBN, $Title));
		return $this->db->affected_rows() > 0;
	}
	
	
	
	# Surveille un ISBN depuis une annonce
	public function watch_isbn_from_ad($AdID = 0)
	{
		if(empty($AdID)) return FALSE;
		
		$this->db->query("REPLACE INTO alerts SELECT ".User::id()." AS UserID, ISBN, Title AS BookTitle FROM ads WHERE AdID = ? LIMIT 1", array($AdID));
		return $this->db->affected_rows() > 0;
	}
	
	
	
	# Arrête de surveiller un ISBN
	public function unwatch_isbn($ISBN = '')
	{
		if(empty($ISBN)) return FALSE;
		
		if(is_array($ISBN))
			$app = "IN (".implode(",", $ISBN).")";
		else
			$app = "= ".$this->db->escape_str($ISBN);
		
		$this->db->query("DELETE FROM alerts WHERE UserID = ? AND ISBN ".$app, array(User::id()));
		return $this->db->affected_rows() > 0;
	}
	
	
	
	# Surveille un ISBN depuis une annonce
	public function unwatch_isbn_from_ad($AdID = 0)
	{
		if(empty($AdID)) return FALSE;
		
		$this->db->query("DELETE FROM alerts WHERE UserID = ? AND ISBN = (SELECT ISBN FROM ads WHERE AdID = ?)", array(User::id(), $AdID));
		return $this->db->affected_rows() > 0;
	}
	
	
	
	# Vérifie si un ISBN est surveillé
	public function is_isbn_watched($ISBN = '')
	{
		if(!valid_isbn($ISBN)) return FALSE;
		if(strlen($ISBN) == 10) $ISBN = convert_isbn($ISBN);
		
		return $this->db->query("SELECT 1 FROM alerts WHERE UserID = ? AND ISBN = ?", array(User::id(), $ISBN))->num_rows() > 0;
	}
	
	
	
	# Compte le nombre de livres surveillés par un utilisateur
	public function count_user_alerts()
	{
		return intval($this->db->query("SELECT COUNT(ISBN) AS C FROM alerts WHERE UserID = ?", array(User::id()))->row()->C);
	}
	
	
	
	# Retourne toutes les alertes d'un utilisateur
	public function get_user_alerts($start = 0, $length = 20)
	{
		return $this->db->query("SELECT ISBN, BookTitle FROM alerts WHERE UserID = ? ORDER BY BookTitle ASC LIMIT ?,?", array(User::id(), $start, $length))->result();
	}
	
	
	
	# Retourne la liste de ceux qui surveillent un livre
	public function get_watchers($ISBN = '')
	{
		if(empty($ISBN)) return FALSE;
		return $this->db->query("SELECT Email, Language FROM users WHERE UserID IN (SELECT UserID FROM alerts WHERE ISBN = ?)", array(convert_isbn($ISBN)))->result();
	}
	
	
	
}

/* End of file schools_model.php */
/* Location: ./application/models/schools_model.php */