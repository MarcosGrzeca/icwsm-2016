UPDATE `tweets` t
SET taxaSubstantivo = (IFNULL(
                                  (SELECT count(ta.id)
                                   FROM tweets_anotacoes ta
                                   WHERE ta.idTweet = t.id
                                       AND pos IN ("NN", "NNS", "NNP", "NNPS") ), 0) / IF(
                                                                                              (SELECT count(ta.id)
                                                                                               FROM tweets_anotacoes ta
                                                                                               WHERE ta.idTweet = t.id
                                                                                                   AND pos IN ("CC", "CD", "DT", "EX", "FW", "IN", "JJ", "JJR", "JJS", "LS", "MD", "NN", "NNS", "NNP", "NNPS", "PDT", "POS", "PRP", "PRP", "RB", "RBR", "RBS", "RP", "SYM", "TO", "UH", "VB", "VBD", "VBG", "VBN", "VBP", "VBZ", "WDT", "WP", "WP", "WRB", "PRP$", "WP$", "#") > 0),
                                                                                              (SELECT count(ta.id)
                                                                                               FROM tweets_anotacoes ta
                                                                                               WHERE ta.idTweet = t.id
                                                                                                   AND pos IN ("CC", "CD", "DT", "EX", "FW", "IN", "JJ", "JJR", "JJS", "LS", "MD", "NN", "NNS", "NNP", "NNPS", "PDT", "POS", "PRP", "PRP", "RB", "RBR", "RBS", "RP", "SYM", "TO", "UH", "VB", "VBD", "VBG", "VBN", "VBP", "VBZ", "WDT", "WP", "WP", "WRB", "PRP$", "WP$", "#") ), 1));


UPDATE `tweets` t
SET taxaAdjetivo = (IFNULL(
                               (SELECT count(ta.id)
                                FROM tweets_anotacoes ta
                                WHERE ta.idTweet = t.id
                                    AND pos IN ("JJ", "JJR", "JJS") ), 0) / IF(
                                                                                   (SELECT count(ta.id)
                                                                                    FROM tweets_anotacoes ta
                                                                                    WHERE ta.idTweet = t.id
                                                                                        AND pos IN ("CC", "CD", "DT", "EX", "FW", "IN", "JJ", "JJR", "JJS", "LS", "MD", "NN", "NNS", "NNP", "NNPS", "PDT", "POS", "PRP", "PRP", "RB", "RBR", "RBS", "RP", "SYM", "TO", "UH", "VB", "VBD", "VBG", "VBN", "VBP", "VBZ", "WDT", "WP", "WP", "WRB", "PRP$", "WP$", "#") > 0),
                                                                                   (SELECT count(ta.id)
                                                                                    FROM tweets_anotacoes ta
                                                                                    WHERE ta.idTweet = t.id
                                                                                        AND pos IN ("CC", "CD", "DT", "EX", "FW", "IN", "JJ", "JJR", "JJS", "LS", "MD", "NN", "NNS", "NNP", "NNPS", "PDT", "POS", "PRP", "PRP", "RB", "RBR", "RBS", "RP", "SYM", "TO", "UH", "VB", "VBD", "VBG", "VBN", "VBP", "VBZ", "WDT", "WP", "WP", "WRB", "PRP$", "WP$", "#") ), 1))
UPDATE `tweets` t
SET taxaAdverbio = (IFNULL(
                               (SELECT count(ta.id)
                                FROM tweets_anotacoes ta
                                WHERE ta.idTweet = t.id
                                    AND pos IN ("RB", "RBR", "RBS") ), 0) / IF(
                                                                                   (SELECT count(ta.id)
                                                                                    FROM tweets_anotacoes ta
                                                                                    WHERE ta.idTweet = t.id
                                                                                        AND pos IN ("CC", "CD", "DT", "EX", "FW", "IN", "JJ", "JJR", "JJS", "LS", "MD", "NN", "NNS", "NNP", "NNPS", "PDT", "POS", "PRP", "PRP", "RB", "RBR", "RBS", "RP", "SYM", "TO", "UH", "VB", "VBD", "VBG", "VBN", "VBP", "VBZ", "WDT", "WP", "WP", "WRB", "PRP$", "WP$", "#") > 0),
                                                                                   (SELECT count(ta.id)
                                                                                    FROM tweets_anotacoes ta
                                                                                    WHERE ta.idTweet = t.id
                                                                                        AND pos IN ("CC", "CD", "DT", "EX", "FW", "IN", "JJ", "JJR", "JJS", "LS", "MD", "NN", "NNS", "NNP", "NNPS", "PDT", "POS", "PRP", "PRP", "RB", "RBR", "RBS", "RP", "SYM", "TO", "UH", "VB", "VBD", "VBG", "VBN", "VBP", "VBZ", "WDT", "WP", "WP", "WRB", "PRP$", "WP$", "#") ), 1));


UPDATE `tweets` t
SET taxaVerbo = (IFNULL(
                               (SELECT count(ta.id)
                                FROM tweets_anotacoes ta
                                WHERE ta.idTweet = t.id
                                    AND pos IN ("VB", "VBD", "VBG", "VBN", "VBP", "VBZ") ), 0) / IF(
                                                                                   (SELECT count(ta.id)
                                                                                    FROM tweets_anotacoes ta
                                                                                    WHERE ta.idTweet = t.id
                                                                                        AND pos IN ("CC", "CD", "DT", "EX", "FW", "IN", "JJ", "JJR", "JJS", "LS", "MD", "NN", "NNS", "NNP", "NNPS", "PDT", "POS", "PRP", "PRP", "RB", "RBR", "RBS", "RP", "SYM", "TO", "UH", "VB", "VBD", "VBG", "VBN", "VBP", "VBZ", "WDT", "WP", "WP", "WRB", "PRP$", "WP$", "#") > 0),
                                                                                   (SELECT count(ta.id)
                                                                                    FROM tweets_anotacoes ta
                                                                                    WHERE ta.idTweet = t.id
                                                                                        AND pos IN ("CC", "CD", "DT", "EX", "FW", "IN", "JJ", "JJR", "JJS", "LS", "MD", "NN", "NNS", "NNP", "NNPS", "PDT", "POS", "PRP", "PRP", "RB", "RBR", "RBS", "RP", "SYM", "TO", "UH", "VB", "VBD", "VBG", "VBN", "VBP", "VBZ", "WDT", "WP", "WP", "WRB", "PRP$", "WP$", "#") ), 1))