<?php
	$Channel_ID = "1510818535";
	$Channel_Secret = "7b03f71e4c835ee13089d7f190c8e932";
	$Channel_Access_Token = "STBMQKRzAJXFqR/61adEjZ1N1fG9UoOYNUkaqcQD3Zb845vqdXvx94gLjkCEqXBfOoyVpIEPHxtUrgg1An7YRSI5pOotWzi2fpnqIneOgpXCIxFYCPFpRtx2cTULTSXsndNWmypJms/+kwjlTeJgRQdB04t89/1O/w1cDnyilFU=";
	$linereplyurl = "https://api.line.me/v2/bot/message/reply";
	$linegetcontenturl = "https://api.line.me/v2/bot/message/{messageId}/content";
	$linegetprofileurl = "https://api.line.me/v2/bot/profile/{userId}";
	$lineleavegroupurl = "https://api.line.me/v2/bot/group/{groupId}/leave";
	$lineleaveroomurl = "https://api.line.me/v2/bot/room/{roomId}/leave";
	
	$httpRequestBody = file_get_contents('php://input');
	$hash = hash_hmac('sha256', $httpRequestBody, $Channel_Secret, true);
	$signature = base64_encode($hash);
	// Compare X-Line-Signature request header string and the signature

	if(strcasecmp($signature, $_SERVER['HTTP_X_LINE_SIGNATURE']) == 0){
		//WriteLog(date("H:i:s")." : ".$httpRequestBody."\n");
		$linebody = $httpRequestBody;

		if(isJson($linebody)){
			$linebody = json_decode($linebody, true);

			$events = $linebody['events'];
			for($i=0; $i<sizeof($events); $i++){
				$replyToken = $events[$i]['replyToken'];
				$type = $events[$i]['type']; //message, follow, join 
				$timestamp = $events[$i]['timestamp'];
				$source = $events[$i]['source'];
					$sourceType = $source['type']; //user, group, room
					if(strcasecmp($sourceType, "user") == 0){
						$userId = $source['userId'];
					}else if(strcasecmp($sourceType, "group") == 0){
						$groupId = $source['groupId'];
					}else if(strcasecmp($sourceType, "room") == 0){
						$roomId = $source['roomId'];
					}
				if(strcasecmp($type, "message") == 0){
					$message = $events[$i]['message'];
						$messageId = $message['id'];
						$messageType = $message['type']; //text only
						if(strcasecmp($messageType, "text") == 0){
							$messageText = trim($message['text']);
							//Reaction Put Below Here
							if(strlen($messageText) > 0){
								if(strcmp(substr($messageText, 0, 1), "/") == 0){ //Bot Command
									if(strcasecmp(substr($messageText, 1), "bantuan") == 0){
										ReplyMessage($linereplyurl, $Channel_Access_Token, $replyToken, array(NewText("*Kata kunci yang dihasilkan adalah sesuai standar \"Password Policy\" (https://en.wikipedia.org/wiki/Password_policy), dan selalu terdiri dari 12 karakter dengan format 6 digit angka, diikuti oleh 4 huruf, dan diakhiri dengan 2 karakter khusus.\nContoh:\nKata Kunci: 123456AZaz!@\n\nApabila suatu website hanya menerima pin (angka) maka kamu hanya perlu menggunakan 6 karakter pertama dari kata kunci yang diberikan, yaitu \"123456\".\nDan apabila website tidak menerima karakter khusus, kamu hanya perlu mengambil 10 karakter dari kata kunci yang diberikan, yaitu \"123456AZaz\".")));
									}else if(strcasecmp(substr($messageText, 1), "ketentuan") == 0){
										ReplyMessage($linereplyurl, $Channel_Access_Token, $replyToken, array(NewText("*Dengan menggunakan \"Pembuat Kunci\", kamu setuju bahwa kamu telat membaca, memahami, menerima dan menyetujui ketentuan ini.\n\n\"Pembuat Kunci\" adalah layanan penyedia kata kunci yang berdiri di atas platform LINE.\n\n\"Pembuat Kunci\" dan semua pengguna \"Pembuat Kunci\" tunduk kepada aturan, ketentuan dan kebijakan LINE, dengan mengindahkan aturan dan/atau tata hukum negara Indonesia.\n\nKata kunci yang dihasilkan oleh \"Pembuat Kunci\" adalah Hak Intelektual dari \"Pembuat Kunci\".\n\nKamu diperbolehkan menyimpan, menyalin, memperbanyak, atau mengubah kata kunci tersebut namun \"Pembuat Kunci\" tidak memiliki tanggung jawab dengan kata kunci tersebut.\n\n\"Pembuat Kunci\" adalah layanan online 24 jam, namun sewaktu-waktu mungkin saja tidak dapat diakses karena suatu kendala.\n\nOleh karenanya, meskipun kamu dapat menanyakan kembali kata kunci yang pernah kamu buat untuk suatu website, kamu tetap disarankan menyimpan hasilnya disuatu tempat yang aman.\n\n\"Pembuat Kunci\" sangat menyarankan menggunakan fitur \"LINE Keep\" untuk menyimpan kata kunci yang dihasilkan.\n\nSehubungan dengan kebijakan privasi pengguna layanan, maka \"Pembuat Kunci\" tidak menyimpan data kamu, alamat website yang pernah kamu kirimkan, maupun kata kunci yang dihasilkan.\n\nKata kunci yang dihasilkan atau ditanyakan kembali oleh kamu dibuat berdasarkan teknik algoritma dengan kunci khusus yang berbeda-beda untuk setiap penggunanya.\n\nBahkan kami sebagai pembuat algoritmanya tidak mengetahui kunci apa yang akan digunakan.\nJadi, kata kunci yang dihasilkan oleh \"Pembuat Kunci\" 100% aman dan tidak akan diketahui oleh pihak mana pun, kecuali kamu sebagai penggunanya.")));
									}else if(strcasecmp(substr($messageText, 1, 11), "cekpassword") == 0){
										$tempStr = substr($messageText, 13);
										$passw = $tempStr;
										if(strcasecmp(substr($messageText, 13), $passw) == 0){
											if(strlen($passw) >10 && preg_match( '/[a-z]+/', $passw ) && 
											  preg_match( '/[A-Z]+/', $passw ) && 
											  preg_match( '/[0-9]+/', $passw ) && 
											  preg_match( '/[[:punct:]]+/', $passw )  ){
												ReplyMessage($linereplyurl, $Channel_Access_Token, $replyToken, array(NewText("Password kamu udah kuat nih! karena udah cukup panjang dan memiliki kombinasi yang kuat!")));
											}else if (strlen($passw) >6 && strlen($passw) <=10 && preg_match( '/[a-z]+/', $passw ) ||
											  preg_match( '/[A-Z]+/', $passw ) || preg_match( '/[0-9]+/', $passw ) && preg_match( '/[[:punct:]]+/', $passw )){
												ReplyMessage($linereplyurl, $Channel_Access_Token, $replyToken, array(NewText("Password kamu belum cukup kuat nih! karena kurang memiliki kombinasi karakter yang kuat, kalian boleh cek password lainnya atau gunakan password yang aku generate aja. hehe \n\nKetik: \"/cekpassword\" untuk cek password kamu yang lain")));
											}else{
												ReplyMessage($linereplyurl, $Channel_Access_Token, $replyToken, array(NewText("Password kamu masih lemah atau rawan banget nih! karena kurang memiliki kombinasi karakter yang kuat, kalian boleh cek password lainnya atau gunakan password yang aku generate aja. hehe \n\nKetik: \"/cekpassword\" untuk cek password kamu yang lain")));
											}
										}
									}
								}else if(strcmp(substr($messageText, 0, 1), "*") == 0){ //Commentary, Bot will not process this
								}else{ //Bot will process this accordingly
									if($userId){
										$domain = getdomain($messageText);
										if($domain){
											$key = make_key($userId, $domain);
											$reactionmsg = NewText("Kata kunci kamu untuk website \"$domain\" adalah $key");
											$reactionmsg2 = NewText("Kamu tidak perlu mengingat kata kunci di atas, bila kelak kamu membutuhkan kembali kata kunci tersebut, kamu dapat menanyakannya kembali dengan mengirimkan saya alamat website yang sama.\n\nAkan tetapi sangat disarankan untuk menyimpannya disuatu tempat yang aman sebagai backup, misalkan pada LINE Keep (Tekan dan tahan pada pesan di atas sampai muncul pop up, kemudian pilih \"Save in Keep\".\n\nSaya tuliskan sekali lagi kata kunci di bawah pesan ini untuk mempermudah kamu melakukan \"Copy\" dan \"Paste\".");
											$reactionmsg3 = NewText($key);
											$reactionmsg4 = NewText("Terima kasih telah membuat kunci di \"Pembuat Kunci\".\n\nKami sangat senang bila kamu menyukai fitur ini dan mau mereferensikannya kepada teman-teman yang lain.");
											ReplyMessage($linereplyurl, $Channel_Access_Token, $replyToken, array($reactionmsg, $reactionmsg2, $reactionmsg3, $reactionmsg4));
										}else{
											$reactionmsg = NewText("Hai teman, alamat website yang kamu kirim tidak saya kenali.\nCoba kirim sekali lagi dengan mengikuti format seperti petunjuk di bawah ini.");
											$reactionmsg2 = NewText("Petunjuk penggunaan: Tulis (atau \"Paste\") alamat website yang kamu maksudkan, lalu kirim ke saya.\nContoh: \"gmail.com\"\nAtau: \"https://www.hotmail.com/\"");
											ReplyMessage($linereplyurl, $Channel_Access_Token, $replyToken, array($reactionmsg, $reactionmsg2));
										}
									}
								}
							}
						}
				}else if(strcasecmp($type, "follow") == 0){
					$reactionmsg = NewText("Hai, saya pembuat kunci.\n\nPernah merasa kesulitan saat diminta membuat sebuah password ketika melakukan registrasi di sebuah website?\nAtau memiliki satu password yang sama untuk website yang berbeda-beda sehingga rawan diretas?\n\nKini kamu tidak perlu bingung lagi.\nSaya dapat membantu kamu mengecek password yang biasa kalian pakai dan membuat password (kata kunci) yang berbeda-beda untuk setiap website, dan kamu juga tidak perlu menghafalnya, cukup bertanya kembali kepada saya.\n\nKetik: \"/cekpassword <password kalian>\" untuk cek keamanan password kamu, atau  \"/bantuan\" atau \"/ketentuan\" untuk penjelasan lebih lanjut.");
					$reactionmsg2 = NewText("Petunjuk penggunaan: Tulis (atau Paste) alamat website yang kamu maksudkan, lalu kirim ke saya.\nContoh: \"gmail.com\"\nAtau: \"https://www.hotmail.com/\" \n atau /cekpassword passwordkamu \n untuk cek password kamu");
					ReplyMessage($linereplyurl, $Channel_Access_Token, $replyToken, array($reactionmsg, $reactionmsg2));
				}else if(strcasecmp($type, "join") == 0){
					$reactionmsg = NewText("Hai, saya pembuat kunci.\n\nPernah merasa kesulitan saat diminta membuat sebuah password (kata kunci) ketika melakukan registrasi di sebuah website?\nAtau memiliki satu password yang sama untuk website yang berbeda-beda sehingga rawan diretas?\n\nKini kamu tidak perlu bingung lagi.\nSaya dapat membantu kamu membuat password yang berbeda-beda untuk setiap website, dan kamu juga tidak perlu menghafalnya, cukup bertanya kembali kepada saya.\n\nKetik: \"/cekpassword\" untuk cek keamanan password kamu, atau  \"/bantuan\" atau \"/ketentuan\" untuk penjelasan lebih lanjut.");
					$reactionmsg2 = NewText("Petunjuk penggunaan: Password adalah hal yang rahasia, sebaiknya tidak dibuat dimana banyak orang yang bisa melihat apa isi dari password kita.\n\nTambahkan saya sebagai teman, dan kamu bisa minta dibuatkan password melalui Private Message (Pesan Pribadi).");
					$reactionmsg3 = NewTemplate("Menu", "confirm", "", "", "Tambah Teman",
							NewAction(array("uri", "message"), array("Ya", "Tidak"), array("https://line.me/R/ti/p/%40hrr4708d", null), array(null, "*Tidak menambah teman.")));
					ReplyMessage($linereplyurl, $Channel_Access_Token, $replyToken, array($reactionmsg, $reactionmsg2, $reactionmsg3));
					if(strlen($groupId) > 0){
						LeaveGroupRoom(str_replace("{groupId}", $groupId, $lineleavegroupurl), $Channel_Access_Token, $groupId, $roomId);
					}else if(strlen($roomId) > 0){
						LeaveGroupRoom(str_replace("{roomId}", $roomId, $lineleaveroomurl), $Channel_Access_Token, $groupId, $roomId);
					}
				}
			}
		}
	}

	function make_key($userId, $url){
		$angka = ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9"];
		$besar = ["A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z"];
		$kecil = ["a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z"];
		$symbol = ["!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "-", "_", "=", "+"];
		$md5_userid = md5($userId);
		$md5_url = md5($url);
		$key = array();
		for($i=0; $i<16; $i++){
			$key[] = hexdec($md5_userid[$i*2].$md5_userid[$i*2+1]) + hexdec($md5_url[$i*2].$md5_url[$i*2+1]);
		}
		for($i=0; $i<6; $i++){
			$key[$i] = $angka[$key[$i]%sizeof($angka)];
		}
		if($key[12] % 6 == 0){
			$key[$i] = $besar[$key[$i]%sizeof($besar)]; $i++;
			$key[$i] = $besar[$key[$i]%sizeof($besar)]; $i++;
			$key[$i] = $kecil[$key[$i]%sizeof($kecil)]; $i++;
			$key[$i] = $kecil[$key[$i]%sizeof($kecil)]; $i++;
		}else if($key[12] % 6 == 1){
			$key[$i] = $besar[$key[$i]%sizeof($besar)]; $i++;
			$key[$i] = $kecil[$key[$i]%sizeof($kecil)]; $i++;
			$key[$i] = $besar[$key[$i]%sizeof($besar)]; $i++;
			$key[$i] = $kecil[$key[$i]%sizeof($kecil)]; $i++;
		}else if($key[12] % 6 == 2){
			$key[$i] = $besar[$key[$i]%sizeof($besar)]; $i++;
			$key[$i] = $kecil[$key[$i]%sizeof($kecil)]; $i++;
			$key[$i] = $kecil[$key[$i]%sizeof($kecil)]; $i++;
			$key[$i] = $besar[$key[$i]%sizeof($besar)]; $i++;
		}else if($key[12] % 6 == 3){
			$key[$i] = $kecil[$key[$i]%sizeof($kecil)]; $i++;
			$key[$i] = $besar[$key[$i]%sizeof($besar)]; $i++;
			$key[$i] = $besar[$key[$i]%sizeof($besar)]; $i++;
			$key[$i] = $kecil[$key[$i]%sizeof($kecil)]; $i++;
		}else if($key[12] % 6 == 4){
			$key[$i] = $kecil[$key[$i]%sizeof($kecil)]; $i++;
			$key[$i] = $besar[$key[$i]%sizeof($besar)]; $i++;
			$key[$i] = $kecil[$key[$i]%sizeof($kecil)]; $i++;
			$key[$i] = $besar[$key[$i]%sizeof($besar)]; $i++;
		}else if($key[12] % 6 == 5){
			$key[$i] = $kecil[$key[$i]%sizeof($kecil)]; $i++;
			$key[$i] = $kecil[$key[$i]%sizeof($kecil)]; $i++;
			$key[$i] = $besar[$key[$i]%sizeof($besar)]; $i++;
			$key[$i] = $besar[$key[$i]%sizeof($besar)]; $i++;
		}
		$key[$i] = $symbol[$key[$i]%sizeof($symbol)]; $i++;
		$key[$i] = $symbol[$key[$i]%sizeof($symbol)];
		$keystring = "";
		for($i=0; $i<12; $i++){
			$keystring .= $key[$i];
		}
		return $keystring;
	}

	function getdomain($url){
		$domain = "";
		$pos = strpos($url, "//"); //find protocol
		if($pos !== false) $url = substr($url, $pos+2); //cut after protocol
		$pos = strpos($url, "/"); //find folder
		if($pos !== false) $url = substr($url, 0, $pos); //cut before folder
		$url = strtolower(trim($url)); //domain name trim and lowered
		$url = explode(".", $url); //split subdomain
		if(sizeof($url) > 1){
			if(sizeof($url) == 4){
				$pos = strpos($url[3], ":"); //find port
				if($pos !== false) $url[3] = substr($url[3], 0, $pos); //cut before port
				if(is_numeric($url[0]) && is_numeric($url[1]) && is_numeric($url[2]) && is_numeric($url[3])){ //is ip numeric valid
					if($url[0]>=0 && $url[0]<=255 && $url[1]>=0 && $url[1]<=255 && $url[2]>=0 && $url[2]<=255 && $url[3]>=0 && $url[3]<=255) $domain = implode(".", $url);
				}
			}
			if(strlen($domain) == 0){
				if(strlen($url[sizeof($url)-1]) == 2){
					$min_length = 3;
				}else{
					$min_length = 2;
				}
				$pos = strpos($url[sizeof($url)-1], ":"); //find port
				if($pos !== false) $url[sizeof($url)-1] = substr($url[sizeof($url)-1], 0, $pos); //cut before port
				for($i=sizeof($url)-1; $i>=0; $i--){
					if(is_valid_domain_name($url[$i])){
						if(strlen($domain) == 0){
							$domain = $url[$i];
						}else{
							$domain = $url[$i].".".$domain;
						}
						if(--$min_length == 0) break;
					}else{
						$domain = "";
						break;
					}
				}
			}
			return (strlen($domain) == 0 ? false : $domain);
		}else{
			return false;
		}
		return $url;
	}

	function is_valid_domain_name($domain_name){
		return (preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $domain_name) //valid chars check
				&& preg_match("/^.{1,253}$/", $domain_name) //overall length check
				&& preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $domain_name)   ); //length of each label
	}
	function LeaveGroupRoom($lineleaveurl, $Channel_Access_Token, $groupId, $roomId){
		$fields = array(
			"groupId" => $groupId,
			"roomId" => $roomId
		);
		$returncurl = curlpost($lineleaveurl, 1, json_encode($fields), $Channel_Access_Token);
		return $returncurl;
	}

	function NewAction($actionType, $actionLabel, $actionString, $actionText){
		$actions = array();
		for($i=0; $i<sizeof($actionType); $i++){
			$action = array( //postback, message, uri
				"type" => $actionType[$i],
				"label" => $actionLabel[$i],
				"data" => (strcasecmp($actionType[$i], "postback") == 0 ? $actionString[$i] : ""),
				"text" => (strcasecmp($actionType[$i], "uri") == 0 ? "" : $actionText[$i]),
				"uri" => (strcasecmp($actionType[$i], "uri") == 0 ? $actionString[$i] : "")
			);
			$actions[] = $action;
		}
		return $actions;
	}
	
	function NewTemplate($altText, $templateType, $thumbnailImageUrl, $templateTitle, $templateText, $actions){
		if(strcasecmp($templateType, "buttons") == 0){
			$template = array(
				"type" => "buttons",
				"thumbnailImageUrl" => $thumbnailImageUrl,
				"title" => $templateTitle,
				"text" => $templateText,
				"actions" => $actions
			);
		}else if(strcasecmp($templateType, "confirm") == 0){
			$template = array(
				"type" => "confirm",
				"text" => $templateText,
				"actions" => $actions
			);
		}else if(strcasecmp($templateType, "carousel") == 0){
			$columns = array();
			for($i=0; $i<sizeof($actions); $i++){
				$column = array(
					"thumbnailImageUrl" => $thumbnailImageUrl[$i],
					"title" => $templateTitle[$i],
					"text" => $templateText[$i],
					"actions" => $actions[$i]
				);
				$columns[] = $column;
			}
			$template = array(
				"type" => "carousel",
				"columns" => $columns
			);
		}
		$msg = array(
			"type" => "template",
			"altText" => $altText,
			"template" => $template
		);
		return $msg;
	}
	
	function NewText($messageText){
		$msg = array(
			"type" => "text",
			"text" => $messageText
		);
		return $msg;
	}
	
	function ReplyMessage($linereplyurl, $Channel_Access_Token, $replyToken, $reactionmsg){
		$fields = array(
			"replyToken" => $replyToken,
			"messages" => $reactionmsg
		);
		$returncurl = curlpost($linereplyurl, 1, json_encode($fields), $Channel_Access_Token);
		return $returncurl;
	}
	
	function curlpost($url, $fields_count, $fields_string, $Channel_Access_Token=null){
		$header = array(
            "Content-Type: application/json",
            'Authorization: Bearer ' . $Channel_Access_Token
        );
        $context = stream_context_create(array(
            "http" => array(
                "method" => "POST",
                "header" => implode("\r\n", $header),
                "content" => $fields_string
            ),
        ));
        $response = file_get_contents($url, false, $context);
		//if(strpos($http_response_header[0], '200') === false) WriteLine($response);
        return $response;
	}

	function isJson($string) {
		json_decode($string);
		return (json_last_error() == JSON_ERROR_NONE);
	}

	function WriteLine($toWrite){
		$myfile = fopen("line.txt", "a+");
		$txt = "$toWrite\n";
		fwrite($myfile, $txt);
		fclose($myfile);
	}

	function WriteLog($toWrite){
		mkdir("logs");
		$folder = date("Y-m");
		mkdir("logs/$folder");
		$myfile = fopen("logs/$folder/".date("Y-m-d").".txt", "a+");
		$txt = "$toWrite\n";
		fwrite($myfile, $txt);
		fclose($myfile);
	}
?>