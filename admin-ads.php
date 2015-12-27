<?php
include_once 'admin-header.php';

if(isset($_POST['submit-ad'])){
	if(strlen($_POST['title']) > 0){

		
		$title = $_POST['title'];
		$news_text = $_POST['news-text'];

		if(isset($_FILES['news-image'])) {
			$file = $_FILES['news-image'];

			$file_name = $file['name'];
			$file_tmp = $file['tmp_name'];
			$file_size = $file['size'];
			$file_error = $file['error'];

			$file_ext = explode('.', $file_name);
			$file_ext = strtolower(end($file_ext));

			$allowed_ext = array('jpg');

			if(in_array($file_ext, $allowed_ext)){

				if($file_error === 0){
					//2mb
					if($file_size < 2097152){

						//Ger filnamnet ett unikt namn så det inte krockar om man lägger upp samma bild flera gånger.
						$file_name_new = uniqid('', true) . '.' . $file_ext;
						$file_destination = '../uploads/' . $file_name_new;

						if(move_uploaded_file($file_tmp, $file_destination)){
							$result = nonQuery("INSERT INTO News (`Title`,`DateTime`,`NewsText`,`NewsImagePath`) VALUES (:title,now(),:news_text,:image)", array(":title" => $title, ":news_text" => $news_text, ":image" => $file_name_new));
							
							if($result["err"] === null){
								//Inlagd med i news med bild
							}
						}
					}
				}
			}else{
				//Ingen bild selectead
				$result = nonQuery("INSERT INTO News (`Title`,`DateTime`,`NewsText`) VALUES (:title,now(),:news_text)", array(":title" => $title, ":news_text" => $news_text));
				
				if($result["err"] === null){
					//Inlagd utan bild
				}
			}
		}

	}

}

?>
<main>
	

</main>
<?php
include_once '../footer.php';
?>