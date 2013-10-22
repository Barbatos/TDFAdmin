<?php

class Admins
{
	private $bdd;

	/* Cette fonction est appelée à chaque début de chargement d'une page */
	public function __construct($db)
	{
		$this->bdd = $db;
		
		/* Si l'utilisateur n'est pas loggué ($_SESSION vide) et qu'on trouve des cookies correspondant 
		 * au site, alors on essaye de le logguer en comparant les hash
		 */
		if(empty($_SESSION['id']) && !empty($_COOKIE['tdfadmin']) && !empty($_COOKIE['tdfadmin_mbrid']))
		{
			$browser = (isset($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT'] : '';

			$query = $this->bdd->prepare('SELECT ID, PASSWORD, PSEUDO FROM TDF_ADMINS WHERE ID = :id');
			$query->bindValue(':id', s($_COOKIE['tdfadmin_mbrid']));
			$query->execute();
			$data = $query->fetch(PDO::FETCH_OBJ);
			$query->closeCursor();
			
			$hash = sha1('efrtHR9078rgtjh98'.sha1($browser).'regKUEFHu'.sha1($data->PSEUDO).'eggEFaqx122'.sha1($data->ID).'egg98876grfPOOIH96'.sha1($data->PASSWORD).'rtyjukilopPOHJ98765GHJKr');
			if($hash == $_COOKIE['tdfadmin'])
			{
				setcookie("tdfadmin", $hash, strtotime("+1 month"), '/');
				setcookie("tdfadmin_mbrid", $data->id, strtotime("+1 month"), '/'); 
					
				$_SESSION['id'] = $data->ID;
			}
			else
			{
				$this->logout();
			}
		}
	}
	
	/* Vérifie si l'admin est loggué */
	public function isLogged()
	{
		if( isset($_SESSION['id']) && ((int)$_SESSION['id'] != 0))
			return true;
		else 
			return false;
	}

	/* Connexion au site */
	public function login()
	{
		$query = $this->bdd->prepare('SELECT ID, PSEUDO, PASSWORD, EMAIL FROM TDF_ADMINS WHERE PSEUDO = :pseudo');
		$query->bindValue(':pseudo', P('pseudo'));
		$query->execute();
		$data = $query->fetch(PDO::FETCH_OBJ);
		$query->closeCursor();
		
		/* pas d'admin trouvé */
		if(!$data->ID)
			message_redirect("Mauvais pseudo ou mot de passe !");
		
		/* Mauvais mot de passe */
		if($data->PASSWORD != sha1(P('password')))
			message_redirect("Mauvais email ou mot de passe !");
		
		$_SESSION['id'] = $data->ID;
		
		$browser = (isset($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT'] : '';

		$hash = sha1('regrIHEFIH'.sha1($browser).'regKUEFHu'.sha1($data->PSEUDO).'eggEFaqx122'.sha1($data->ID).'egg98876grfPOOIH96'.sha1($data->PASSWORD).'rtyjukilopPOHJ98765GHJKr');
		setcookie("tdfadmin", $hash, strtotime("+1 month"), '/'); // 1 mois
		setcookie("tdfadmin_mbrid", $data->ID, strtotime("+1 month"), '/'); // 1 mois
			
		message_redirect('Vous êtes maintenant connecté :-)', '', 1);
	}
	
	/* Déconnexion au site */
	public function logout()
	{
		session_destroy();
		
		setcookie("tdfadmin", false, strtotime("-1 month"), '/');
		setcookie("tdfadmin_mbrid", false, strtotime("-1 month"), '/');
		
		$_SESSION = array();
		$_COOKIE = array();
		
		session_start();
		
		message_redirect('Vous êtes maintenant déconnecté, à bientôt !', '', 1);
	}

	/* Retourne l'ID de l'admin. 
	 * Si le paramètre $id est renseigné alors cette fonction permet de 
	 * vérifier si un ID existe. Sinon elle permet simplement de récupérer 
	 * l'ID de l'admin actuellement connecté
	 */
	public function getID($id = null)
	{
		$SearchID = $this->bdd->prepare('SELECT ID FROM TDF_ADMINS WHERE ID = :id');
		if($id != null)
			$SearchID->execute(array(':id' => s($id)));
		else
			$SearchID->execute(array(':id' => s($_SESSION['id'])));
		
		$data = $SearchID->fetch(PDO::FETCH_OBJ);
		$SearchID->closeCursor();
		
		if(!empty($data->ID))
			return $data->ID;
		else 
			return false;
	}
	
	/* Récupère le pseudo de l'utilisateur */
	public function getPseudo($id = null)
	{
		$SearchName = $this->bdd->prepare('SELECT PSEUDO FROM TDF_ADMINS WHERE ID = :id');
		if($id != null)
			$SearchName->execute(array(':id' => s($id)));
		else
			$SearchName->execute(array(':id' => s($_SESSION['id'])));
		
		$data = $SearchName->fetch(PDO::FETCH_OBJ);
		$SearchName->closeCursor();
		
		return $data->PSEUDO;
	}
	
	/* Retourne toutes les infos d'un admin */
	public function getInfos($id = null)
	{
		$SearchInfos = $this->bdd->prepare('SELECT * FROM TDF_ADMINS WHERE ID = :id');
		if($id != null)
			$SearchInfos->execute(array(':id' => s($id)));
		else
			$SearchInfos->execute(array(':id' => s($_SESSION['id'])));
			
		$data = $SearchInfos->fetch(PDO::FETCH_OBJ);
		$SearchInfos->closeCursor();
		
		return $data;
	}
	 
	/* Retourne la liste de tous les admins
	 */
	public function getListeAdmins()
	{
		$query = $this->bdd->prepare('SELECT * FROM TDF_ADMINS
			ORDER BY ID ASC');
		$query->execute();
		$data = $query->fetchAll(PDO::FETCH_OBJ);
		$query->closeCursor();
		
		return $data;
	}
	
}
