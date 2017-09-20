SELECT t.id,
       q1 AS resposta,
       textParser,
       textoParserEmoticom AS textoCompleto,
       hashtags,
       emoticonPos,
       emoticonNeg,
       sentiment,
       sentimentH,
       localCount,
       organizationCount,
       moneyCount,
       personCount,
       erroParseado AS numeroErros,
       numeroConjuncoes,
       taxaSubstantivo,
       taxaAdjetivo,
       taxaAdverbio,
       taxaVerbo,
       palavroes,
       hora,
       tl.name AS nomeEstabelecimento,
       tl.category AS categoriaEstabelecimento,
       diaSemana,
  (SELECT GROUP_CONCAT(replace(ty.subject, 'http://dbpedia.org/resource/Category:', ''))
   FROM tweets_nlp tn
   JOIN conceito c ON c.palavra = tn.palavra
   JOIN resource_subject ty ON ty.resource = c.resource
   WHERE tn.idTweetInterno = t.idInterno
   AND ty.escolhida = 1
   GROUP BY t.id) AS entidades,

  (SELECT GROUP_CONCAT(replace(ty.subject, 'http://dbpedia.org/resource/Category:', ''))
   FROM tweets_gram tn
   JOIN conceito c ON c.palavra = tn.palavra
   JOIN resource_subject ty ON ty.resource = c.resource
   WHERE tn.idTweetInterno = t.idInterno
   AND escolhida = 1
   GROUP BY t.id) AS grams
FROM tweets t
LEFT JOIN tweet_localizacao tl ON tl.idTweetInterno = t.idInterno
AND distance = 100
LIMIT 10