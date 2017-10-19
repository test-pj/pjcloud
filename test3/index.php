<!DOCTYPE html>
<html>
	<head>

		<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
		<link rel="stylesheet" media="screen" type="text/css" title="Design" href="css3.css" />
		<title>SCANCLOUD</title>
		<h1>SCANCLOUD - Historique d'actualisation OwnCloud</h1>
		
	</head>
	
	<body>
		<a href="/test3/">Accueil</a><br><br>
		<form id="action" method="get">
		Filtre nom fichier:<input type="text" id="fichier" name="fichier" ><br>

		<?php
			// Démarrer une session pour la variable de session

			session_start();

			// Rapport d'erreurs doit modifier php.ini avec "display_errors = on"
			ini_set('display_errors', 1);
			ini_set('display_startup_errors', 1);
			error_reporting(E_ALL);

			// vérifie la pagination est définie sinon valeur de 10
			if ( isset( $_GET[ "pagination" ] ) )
			{
				$pagination = $_GET["pagination"];
			}
			else
			{
				$pagination = 100;
			}






			// On utilise PDO pour la BD car c'est plus sécurisé et rapide en t'autre...
			// connexion a la DB 

			$db = new PDO('mysql:host=localhost;dbname=owncloud;charset=utf8mb4', 'root', '');
			if ( isset( $_GET[ "page" ] ) )
			{
				$page = $_GET["page"];
			}
			
			else
			{
				$page = 1;
			}


			echo '<input type="hidden" id="page" name="page" value=					  "'.$page.'" >';
			echo '<input type="hidden" id="pagination" name="pagination" value="'.$pagination.'" >';
		?>
			<input type="submit" value="filtre" >
			</form>


		<?php

			// Vérification si filtre fichier est sélectionné


			if ( isset( $_GET[ "fichier" ] ) )
			{
				$fichier = $_GET[ "fichier" ];
			}
			
			else
			{
				$fichier = "";
			}

			if ( isset( $_GET[ "page" ] ) )
			{
				$page = $_GET[ "page" ];
			}
			
			else
			{
				$page = 1;
			}
			// Compter le nombre de ranger de la DB

			echo "<br>";
			echo "Pagination: ".$pagination."<br><br>";

			if ($fichier == "")
			{
				$stmt = $db->prepare('SELECT * FROM oc_activity ORDER BY activity_id');
				$stmt->execute(array($fichier));
				$arr = $stmt->fetchAll(PDO::FETCH_ASSOC);

				$combien = count($arr);
			}
			
			else
			{
				$stmt = $db->prepare('SELECT * 
												  FROM oc_activity 
												  WHERE file
												  LIKE "%'.$fichier.'%" 
												  ORDER BY activity_id');
				$stmt->execute(array($fichier));
				$arr = $stmt->fetchAll(PDO::FETCH_ASSOC);

				$combien = count($arr);
			}

			$compteur = 1;

			echo "Nombre de ranger: ".$combien."<br><br>";
			$pages = $combien / $pagination;


			$offset = ($pagination * $page) - $pagination;
			echo "offset: ".$offset;
			echo "<br>page: ".$page;
			$roundpages = ceil($pages);

			if ($fichier == "")
			{
				$requete = $db->query('SELECT * 
													FROM oc_activity 
													ORDER BY activity_id 
													ASC LIMIT '.$pagination.' 
													OFFSET '.$offset);
			}
			
			else
			{
				echo "<br>Fichier recherché: ".$fichier."<br>";
				$requete = $db->query('SELECT *
													FROM oc_activity 
													WHERE file LIKE "%'	.$fichier.'%" 
													ORDER BY activity_id 
													ASC LIMIT '					.$pagination.' 
													OFFSET '						.$offset);
			}
			// Boucle foreach à travers la BD
			echo "<table>
				<p style='color: blue;'>
					<tr>
				
							<td><h2> user				      </h2></td>
							<td><h2> activit_id			  </h2></td>
							<td><h2> affecteduser 		  </h2></td>
							<td><h2> app 					  </h2></td>
							<td><h2> subjectparams	  </h2></td>
							<td><h2> date 				  </h2></td>
							<td><h2> file					  </h2></td>
					</tr>";


			foreach($requete as $row)
			{
 
				echo "<tr><td>"										.$row['user'].                  "</td>".
							   "<td>"											.$row['activity_id'].		"</td>".
							   "<td>"											.$row['affecteduser'].	"</td>".
							   "<td>"									        .$row['app'].					"</td>". 
							   "<td>"                                          .$row['subjectparams']."</td>".
							   "<td>".date('"D M j G:i:s T Y"', $row['timestamp']).		"</td>".
								"<td>"						                .$row['file'].					"</td>".
						"<tr>";

			}
			
			?>
			<table id="pagination">
				<?php
		
						//	echo '#'.$compteur.'  |  activity_id: '.$row['activity_id'].'  |  timestamp: '.date('"D M j G:i:s T Y"', $row['timestamp']).'  |  priority: '.$row['priority'].'  |
						//	type: '.$row['type'].'  |  user: '.$row['user'].'  |  affecteduser: '.$row['affecteduser'].'  |  app: '.$row['app'].'  |  subject: '.$row['subject'].'  |  subjectparams: '.$row['subjectparams'].'  |  
						//message: '.$row['message'].'  |  messageparams: '.$row['messageparams'].'  |  file: '.$row['file']."<br><br>"; //etc...
				$compteur++;

			
				echo "</table>";

				$x=1;

				if ( isset($_GET[ "page" ]))
				{
					$page = $_GET[ "page" ];
				}
				
				else
				{
					$page =1;
				}
				while ($x <= $roundpages)
				{
					if ($x == $page)
					{
						echo " ".$x." ";
					}
					
					else
					{
						echo "<a href='/test3/index.php?page=" .$x.
															  "&pagination=" .$pagination.
															  "&fichier="	     .$fichier.
															  "'>"				    .$x.			
								"</a> ";
					}

					$x++;
				}


			?>
			
			<form id="action" method="get">
			Filtre pagination:<input type="text" id="pagination" name="pagination" ><br>
		</table>	
			<?php
				echo '<input type="hidden" id="page" name="page" value="'.$page.'" >';
				echo '<input type="hidden" id="fichier" name="fichier" value="'.$fichier.'" >';
			?>
		</form>


	</body>
</html>
