--
-- Struktura tabulky `guessNumber`
--

CREATE TABLE IF NOT EXISTS `guessNumber` (
  `id` int(11) NOT NULL,
  `twitchUser_id` int(11) NOT NULL,
  `channel_id` int(11) NOT NULL,
  `points` int(11) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `twitchUserName` varchar(255) COLLATE utf8_czech_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Klíče pro tabulku `guessNumber`
--
ALTER TABLE `guessNumber`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pro tabulku `guessNumber`
--
ALTER TABLE `guessNumber`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;