<?php

//SELECT * FROM `tweets` WHERE calais IS NOT NULL AND calais NOT LIKE ('{"doc":{"info":%')

require_once("config.php");

$tweets = query('select id, SUM(total) as totalP from (
  SELECT count(*) as total, id FROM `tweets` WHERE textParser LIKE "% Ankle-biters %" OR textParser LIKE ("Ankle-biters %") OR textParser LIKE ("% Ankle-biters") OR textParser LIKE ("Ankle-biters") GROUP by id
  UNION ALL
  SELECT count(*) as total, id FROM `tweets` WHERE textParser LIKE "% Arse %" OR textParser LIKE ("Arse %") OR textParser LIKE ("% Arse") OR textParser LIKE ("Arse") GROUP by id
  UNION ALL
  SELECT count(*) as total, id FROM `tweets` WHERE textParser LIKE "% Arsehole %" OR textParser LIKE ("Arsehole %") OR textParser LIKE ("% Arsehole") OR textParser LIKE ("Arsehole") GROUP by id
  UNION ALL
  SELECT count(*) as total, id FROM `tweets` WHERE textParser LIKE "% Ass %" OR textParser LIKE ("Assx %") OR textParser LIKE ("% Assx") OR textParser LIKE ("Assx") GROUP by id
  UNION ALL
  SELECT count(*) as total, id FROM `tweets` WHERE textParser LIKE "% asshole %" OR textParser LIKE ("asshole %") OR textParser LIKE ("% asshole") OR textParser LIKE ("asshole") GROUP by id
  UNION ALL
  SELECT count(*) as total, id FROM `tweets` WHERE textParser LIKE "% bastard %" OR textParser LIKE ("bastard %") OR textParser LIKE ("% bastard") OR textParser LIKE ("bastard") GROUP by id
  UNION ALL
  SELECT count(*) as total, id FROM `tweets` WHERE textParser LIKE "% Berk %" OR textParser LIKE ("Berk %") OR textParser LIKE ("% Berk") OR textParser LIKE ("Berk") GROUP by id
  UNION ALL
  SELECT count(*) as total, id FROM `tweets` WHERE textParser LIKE "% bitch %" OR textParser LIKE ("bitch %") OR textParser LIKE ("% bitch") OR textParser LIKE ("bitch") GROUP by id
  UNION ALL
  SELECT count(*) as total, id FROM `tweets` WHERE textParser LIKE "% Bite me %" OR textParser LIKE ("Bite me %") OR textParser LIKE ("% Bite me") OR textParser LIKE ("Bite me") GROUP by id
  UNION ALL
  SELECT count(*) as total, id FROM `tweets` WHERE textParser LIKE "% Blast %" OR textParser LIKE ("Blast %") OR textParser LIKE ("% Blast") OR textParser LIKE ("Blast") GROUP by id
  UNION ALL
  SELECT count(*) as total, id FROM `tweets` WHERE textParser LIKE "% cock %" OR textParser LIKE ("cock %") OR textParser LIKE ("% cock") OR textParser LIKE ("cock") GROUP by id
  UNION ALL
  SELECT count(*) as total, id FROM `tweets` WHERE textParser LIKE "% crap %" OR textParser LIKE ("crap %") OR textParser LIKE ("% crap") OR textParser LIKE ("crap") GROUP by id
  UNION ALL
  SELECT count(*) as total, id FROM `tweets` WHERE textParser LIKE "% Crickhold %" OR textParser LIKE ("Crickhold %") OR textParser LIKE ("% Crickhold") OR textParser LIKE ("Crickhold") GROUP by id
  UNION ALL
  SELECT count(*) as total, id FROM `tweets` WHERE textParser LIKE "% Daft as a bush %" OR textParser LIKE ("Daft as a bush %") OR textParser LIKE ("% Daft as a bush") OR textParser LIKE ("Daft as a bush") GROUP by id
  UNION ALL
  SELECT count(*) as total, id FROM `tweets` WHERE textParser LIKE "% damn %" OR textParser LIKE ("damn %") OR textParser LIKE ("% damn") OR textParser LIKE ("damn") GROUP by id
  UNION ALL
  SELECT count(*) as total, id FROM `tweets` WHERE textParser LIKE "% darn %" OR textParser LIKE ("darn %") OR textParser LIKE ("% darn") OR textParser LIKE ("darn") GROUP by id
  UNION ALL
  SELECT count(*) as total, id FROM `tweets` WHERE textParser LIKE "% Dead from the neck up %" OR textParser LIKE ("Dead from the neck up %") OR textParser LIKE ("% Dead from the neck up") OR textParser LIKE ("Dead from the neck up") GROUP by id
  UNION ALL
  SELECT count(*) as total, id FROM `tweets` WHERE textParser LIKE "% dick %" OR textParser LIKE ("dick %") OR textParser LIKE ("% dick") OR textParser LIKE ("dick") GROUP by id
  UNION ALL
  SELECT count(*) as total, id FROM `tweets` WHERE textParser LIKE "% douche %" OR textParser LIKE ("douche %") OR textParser LIKE ("% douche") OR textParser LIKE ("douche") GROUP by id
  UNION ALL
  SELECT count(*) as total, id FROM `tweets` WHERE textParser LIKE "% fag %" OR textParser LIKE ("fag %") OR textParser LIKE ("% fag") OR textParser LIKE ("fag") GROUP by id
  UNION ALL
  SELECT count(*) as total, id FROM `tweets` WHERE textParser LIKE "% Fornication %" OR textParser LIKE ("Fornication %") OR textParser LIKE ("% Fornication") OR textParser LIKE ("Fornication") GROUP by id
  UNION ALL
  SELECT count(*) as total, id FROM `tweets` WHERE textParser LIKE "% fuck %" OR textParser LIKE ("fuck %") OR textParser LIKE ("% fuck") OR textParser LIKE ("fuck") GROUP by id
  UNION ALL
  SELECT count(*) as total, id FROM `tweets` WHERE textParser LIKE "% Git %" OR textParser LIKE ("Git %") OR textParser LIKE ("% Git") OR textParser LIKE ("Git") GROUP by id
  UNION ALL
  SELECT count(*) as total, id FROM `tweets` WHERE textParser LIKE "% Gormless %" OR textParser LIKE ("Gormless %") OR textParser LIKE ("% Gormless") OR textParser LIKE ("Gormless") GROUP by id
  UNION ALL
  SELECT count(*) as total, id FROM `tweets` WHERE textParser LIKE "% Junky %" OR textParser LIKE ("Junky %") OR textParser LIKE ("% Junky") OR textParser LIKE ("Junky") GROUP by id
  UNION ALL
  SELECT count(*) as total, id FROM `tweets` WHERE textParser LIKE "% Knob %" OR textParser LIKE ("Knob %") OR textParser LIKE ("% Knob") OR textParser LIKE ("Knob") GROUP by id
  UNION ALL
  SELECT count(*) as total, id FROM `tweets` WHERE textParser LIKE "% Manky %" OR textParser LIKE ("Manky %") OR textParser LIKE ("% Manky") OR textParser LIKE ("Manky") GROUP by id
  UNION ALL
  SELECT count(*) as total, id FROM `tweets` WHERE textParser LIKE "% Mingebag %" OR textParser LIKE ("Mingebag %") OR textParser LIKE ("% Mingebag") OR textParser LIKE ("Mingebag") GROUP by id
  UNION ALL
  SELECT count(*) as total, id FROM `tweets` WHERE textParser LIKE "% Moron %" OR textParser LIKE ("Moron %") OR textParser LIKE ("% Moron") OR textParser LIKE ("Moron") GROUP by id
  UNION ALL
  SELECT count(*) as total, id FROM `tweets` WHERE textParser LIKE "% Motherfucker %" OR textParser LIKE ("Motherfucker %") OR textParser LIKE ("% Motherfucker") OR textParser LIKE ("Motherfucker") GROUP by id
  UNION ALL
  SELECT count(*) as total, id FROM `tweets` WHERE textParser LIKE "% Pillock %" OR textParser LIKE ("Pillock %") OR textParser LIKE ("% Pillock") OR textParser LIKE ("Pillock") GROUP by id
  UNION ALL
  SELECT count(*) as total, id FROM `tweets` WHERE textParser LIKE "% piss %" OR textParser LIKE ("piss %") OR textParser LIKE ("% piss") OR textParser LIKE ("piss") GROUP by id
  UNION ALL
  SELECT count(*) as total, id FROM `tweets` WHERE textParser LIKE "% Prat %" OR textParser LIKE ("Prat %") OR textParser LIKE ("% Prat") OR textParser LIKE ("Prat") GROUP by id
  UNION ALL
  SELECT count(*) as total, id FROM `tweets` WHERE textParser LIKE "% pussy %" OR textParser LIKE ("pussy %") OR textParser LIKE ("% pussy") OR textParser LIKE ("pussy") GROUP by id
  UNION ALL
  SELECT count(*) as total, id FROM `tweets` WHERE textParser LIKE "% pussyasshole %" OR textParser LIKE ("pussyasshole %") OR textParser LIKE ("% pussyasshole") OR textParser LIKE ("pussyasshole") GROUP by id
  UNION ALL
  SELECT count(*) as total, id FROM `tweets` WHERE textParser LIKE "% shit %" OR textParser LIKE ("shit %") OR textParser LIKE ("% shit") OR textParser LIKE ("shit") GROUP by id
  UNION ALL
  SELECT count(*) as total, id FROM `tweets` WHERE textParser LIKE "% slut %" OR textParser LIKE ("slut %") OR textParser LIKE ("% slut") OR textParser LIKE ("slut") GROUP by id
  UNION ALL
  SELECT count(*) as total, id FROM `tweets` WHERE textParser LIKE "% Twat %" OR textParser LIKE ("Twat %") OR textParser LIKE ("% Twat") OR textParser LIKE ("Twat") GROUP by id
  UNION ALL
  SELECT count(*) as total, id FROM `tweets` WHERE textParser LIKE "% Twit %" OR textParser LIKE ("Twit %") OR textParser LIKE ("% Twit") OR textParser LIKE ("Twit") GROUP by id
  UNION ALL
  SELECT count(*) as total, id FROM `tweets` WHERE textParser LIKE "% Wanker %" OR textParser LIKE ("Wanker %") OR textParser LIKE ("% Wanker") OR textParser LIKE ("Wanker") GROUP by id
  UNION ALL
  SELECT count(*) as total, id FROM `tweets` WHERE textParser LIKE "% Your dope %" OR textParser LIKE ("Your dope %") OR textParser LIKE ("% Your dope") OR textParser LIKE ("Your dope") GROUP by id
  UNION ALL
  SELECT count(*) as total, id FROM `tweets` WHERE textParser LIKE "% Your jerk %" OR textParser LIKE ("Your jerk %") OR textParser LIKE ("% Your jerk") OR textParser LIKE ("Your jerk") GROUP by id
  UNION ALL
  SELECT count(*) as total, id FROM `tweets` WHERE textParser LIKE "% your prick %" OR textParser LIKE ("your prick %") OR textParser LIKE ("% your prick") OR textParser LIKE ("your prick") GROUP by id
) palavrooo GROUP BY id;');

debug(getNumRows($tweets));
foreach (getRows($tweets) as $key => $value) {
  try {
    $update = "UPDATE `tweets` SET palavroes = '" . $value["totalP"] . "' WHERE id = '" . $value["id"]. "';";
    query($update);
  } catch (Exception $e) {
    var_dump($e);
  }
}

echo "FIM";