-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 01, 2018 at 02:48 PM
-- Server version: 5.7.21-log
-- PHP Version: 7.2.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `proiectmds`
--

-- --------------------------------------------------------

--
-- Table structure for table `abonati_newsletter`
--

CREATE TABLE `abonati_newsletter` (
  `a_id` int(11) NOT NULL,
  `a_nume` varchar(256) NOT NULL,
  `a_cod_verificare` varchar(256) NOT NULL,
  `a_email` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `abonati_newsletter`
--

INSERT INTO `abonati_newsletter` (`a_id`, `a_nume`, `a_cod_verificare`, `a_email`) VALUES
(9, 'Eduard', '9833da196df82501b686cf795ee43adb4a059207a389e9eb4c40ffc92115333f', 'latcan_eduard@yahoo.com'),
(10, 'Bobi', '31363282c33870a0dc8f0baa464369f8fa13f50037a3c4b0bbb4145ba7aca4be', 'bobipreda@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `articole`
--

CREATE TABLE `articole` (
  `a_id` int(11) NOT NULL,
  `a_titlu` varchar(256) NOT NULL,
  `a_text` text NOT NULL,
  `a_autor` varchar(256) NOT NULL,
  `a_data` datetime NOT NULL,
  `a_img_name` varchar(256) NOT NULL,
  `a_descriere` varchar(350) NOT NULL,
  `a_categorie` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `articole`
--

INSERT INTO `articole` (`a_id`, `a_titlu`, `a_text`, `a_autor`, `a_data`, `a_img_name`, `a_descriere`, `a_categorie`) VALUES
(1, 'Antrenament Upper-Lower', 'Moving on, the fourth type of workout to think about is an upper/lower body split. This set-up is typically performed on a two on, one off schedule and allows you to hit each muscle group twice per week.\r\nMoving on, the fourth type of workout to think about is an upper/lower body split. This set-up is typically performed on a two on, one off schedule and allows you to hit each muscle group twice per week.\r\nMoving on, the fourth type of workout to think about is an upper/lower body split. This set-up is typically performed on a two on, one off schedule and allows you to hit each muscle group twice per week.\r\nMoving on, the fourth type of workout to think about is an upper/lower body split. This set-up is typically performed on a two on, one off schedule and allows you to hit each muscle group twice per week.', 'RG', '2018-04-20 13:16:02', 'articol-ul1.jpg', 'Antrenament Upper-Lower - Moving on, the fourth type of workout to think about is an upper/lower body split. \nThis set-up is typically performed on a two on, one off schedule and allows you to hit each muscle group twice per week. \nMoving on, the fourth type of workout to think about is an upper/lower body split.', 1),
(2, 'Antrenament Fullbody', 'Lastly we come to full-body workouts. The 5 x 5 program could also \r\nbe considered a full-body workout program to a degree, since you work almost all the major \r\nmuscle \r\ngroups with the three exercises you choose. But, true full-body programs will \r\nprovide one direct exercise for each muscle group - quads, hamstrings, chest, back and \r\nshoulders (arms are worked when doing chest and back).Lastly we come to full-body workouts. The 5 x 5 program could also \r\nbe considered a full-body workout program to a degree, since you work almost all the major \r\nmuscle \r\ngroups with the three exercises you choose. But, true full-body programs will \r\nprovide one direct exercise for each muscle group - quads, hamstrings, chest, back and \r\nshoulders (arms are worked when doing chest and back).', 'GH', '2018-04-20 13:17:49', 'articol-fb1.jpg', 'Antrenament Fullbody - Moving on, the fourth type of workout to \nthink about is an upper/lower body split. \nThis set-up is typically performed on a two on, \none off schedule and allows you to hit each muscle group twice per week.', 2),
(3, 'Antrenament Push-Pull-Legs', 'Moving on, the fourth type of workout to think about is an upper/lower body split. This set-up is typically performed on a two on, one off schedule and allows you to hit each muscle group twice per week. Moving on, the fourth type of workout to think about is an upper/lower body split. This set-up is typically performed oMoving on, the fourth type of workout to think about is an upper/lower body split. This set-up is typically performed on a two on, one off schedule and allows you to hit each muscle group twice per week. Moving on, the fourth type of workout to think about is an upper/lower body split. This set-up is typically performed o\r\nPush-Pull-Legs', 'Bobi', '2018-04-25 17:18:10', 'articol-ppl1.jpg', 'Antrenament Push-Pull-Legs - Moving on, the fourth type of workout to think about is an upper/lower body split. \nThis set-up is typically performed on a two on, one off schedule and allows you to hit each muscle group twice per week. \nMoving on, the fourth type of workout to think about is an upper/lower body split.', 1),
(4, 'Antrenament pe grupe', 'Antrenament Push-Pull-Legs - Moving on, the fourth type of workout to think about is an upper/lower body split. \nThis set-up is typically performed on a two on, one off schedule and allows you to hit each muscle group twice per week. \nMoving on, the fourth type of workout to think about is an upper/lower body split.', 'ABC', '2018-04-26 19:22:00', 'articol-grupe1.jpg', 'Antrenament pe grupe', 2);

--
-- Triggers `articole`
--
DELIMITER $$
CREATE TRIGGER `creste_nr_articole` AFTER INSERT ON `articole` FOR EACH ROW update categorii_articole
set categorii_articole.c_nr_articole = (select count(*) from articole where a_categorie = new.a_categorie) 
where categorii_articole.c_id = new.a_categorie
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `scade_nr_articole` AFTER DELETE ON `articole` FOR EACH ROW update categorii_articole
set c_nr_articole = (select count(*) from articole where a_categorie = old.a_categorie) 
where c_id = old.a_categorie
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `categorii_articole`
--

CREATE TABLE `categorii_articole` (
  `c_id` int(11) NOT NULL,
  `c_nume` varchar(256) NOT NULL,
  `c_nr_articole` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `categorii_articole`
--

INSERT INTO `categorii_articole` (`c_id`, `c_nume`, `c_nr_articole`) VALUES
(1, 'antrenament', 2),
(2, 'nutritie', 2);

-- --------------------------------------------------------

--
-- Table structure for table `categorii_produse`
--

CREATE TABLE `categorii_produse` (
  `categorie_id` int(11) NOT NULL,
  `categorie_nume` varchar(256) NOT NULL,
  `categorie_nr_produse` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `categorii_produse`
--

INSERT INTO `categorii_produse` (`categorie_id`, `categorie_nume`, `categorie_nr_produse`) VALUES
(1, 'Creatina', 5),
(2, 'Proteine', 5),
(3, 'Gainere', 1);

-- --------------------------------------------------------

--
-- Table structure for table `comentarii_articole`
--

CREATE TABLE `comentarii_articole` (
  `a_id` int(11) NOT NULL,
  `c_id` int(11) NOT NULL,
  `u_id` int(11) NOT NULL,
  `c_text` text NOT NULL,
  `c_data` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `comentarii_articole`
--

INSERT INTO `comentarii_articole` (`a_id`, `c_id`, `u_id`, `c_text`, `c_data`) VALUES
(4, 22, 6, 'Test cu data 2', '2018-05-03 22:13:21'),
(4, 23, 6, 't3g3g33gg3', '2018-05-03 22:13:51'),
(1, 33, 10, 'Test cu raportare comentariu.', '2018-05-06 12:38:08'),
(3, 34, 6, 'test', '2018-05-10 01:04:19'),
(4, 35, 6, 'Comentariu cu &#39;ghilimele&#39;.', '2018-05-18 18:29:09'),
(2, 36, 6, 'test', '2018-06-01 15:31:01'),
(2, 37, 6, 'test2', '2018-06-01 15:31:21'),
(2, 38, 6, 'test3', '2018-06-01 15:33:52'),
(2, 39, 6, 'test\\r\\ntest', '2018-06-01 15:36:53'),
(2, 40, 6, 'test\r\ncu\r\nspatiu', '2018-06-01 15:37:59');

-- --------------------------------------------------------

--
-- Table structure for table `comentarii_produse`
--

CREATE TABLE `comentarii_produse` (
  `c_id` int(11) NOT NULL,
  `c_p_id` int(11) NOT NULL,
  `c_u_id` int(11) NOT NULL,
  `c_text` text NOT NULL,
  `c_data` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `comentarii_produse`
--

INSERT INTO `comentarii_produse` (`c_id`, `c_p_id`, `c_u_id`, `c_text`, `c_data`) VALUES
(8, 3, 10, 'test', '2018-05-06 19:38:54');

-- --------------------------------------------------------

--
-- Table structure for table `comenzi`
--

CREATE TABLE `comenzi` (
  `comanda_id` int(11) NOT NULL,
  `comanda_u_id` int(11) NOT NULL,
  `comanda_status` varchar(256) NOT NULL DEFAULT 'nepreluata',
  `comanda_data_creata` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `comanda_data_update` datetime DEFAULT NULL,
  `comanda_taxa_transport` int(11) NOT NULL DEFAULT '12',
  `comanda_informatii_sup` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `comenzi`
--

INSERT INTO `comenzi` (`comanda_id`, `comanda_u_id`, `comanda_status`, `comanda_data_creata`, `comanda_data_update`, `comanda_taxa_transport`, `comanda_informatii_sup`) VALUES
(1, 6, 'preluata', '2018-05-09 22:28:41', NULL, 0, '');

--
-- Triggers `comenzi`
--
DELIMITER $$
CREATE TRIGGER `scoate_date_utilizator_comanda` BEFORE DELETE ON `comenzi` FOR EACH ROW delete from date_utilizator_comanda where date_utilizator_comanda.comanda_id = old.comanda_id
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `scoate_detalii_comanda` BEFORE DELETE ON `comenzi` FOR EACH ROW delete from comenzi_detalii where cd_c_id = old.comanda_id
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `comenzi_detalii`
--

CREATE TABLE `comenzi_detalii` (
  `cd_id` int(11) NOT NULL,
  `cd_c_id` int(11) NOT NULL,
  `cd_p_id` int(11) NOT NULL,
  `cd_p_cantitate` int(11) NOT NULL,
  `cd_p_pret` int(11) NOT NULL,
  `cd_p_pret_total` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `comenzi_detalii`
--

INSERT INTO `comenzi_detalii` (`cd_id`, `cd_c_id`, `cd_p_id`, `cd_p_cantitate`, `cd_p_pret`, `cd_p_pret_total`) VALUES
(1, 1, 4, 1, 65, 65);

--
-- Triggers `comenzi_detalii`
--
DELIMITER $$
CREATE TRIGGER `creste_cantitate_produs` AFTER DELETE ON `comenzi_detalii` FOR EACH ROW update produse
set produs_cantitate = produs_cantitate + old.cd_p_cantitate 
where produs_id = old.cd_p_id
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `scade_cantitate_produs` AFTER INSERT ON `comenzi_detalii` FOR EACH ROW update produse
set produs_cantitate = produs_cantitate - new.cd_p_cantitate 
where produs_id = new.cd_p_id
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `date_utilizator_comanda`
--

CREATE TABLE `date_utilizator_comanda` (
  `id` int(11) NOT NULL,
  `comanda_id` int(11) NOT NULL,
  `u_id` int(11) NOT NULL,
  `nume` varchar(256) NOT NULL,
  `prenume` varchar(256) NOT NULL,
  `telefon` varchar(256) NOT NULL,
  `judet` varchar(256) NOT NULL,
  `localitate` varchar(256) NOT NULL,
  `strada` varchar(256) NOT NULL,
  `bloc` varchar(256) NOT NULL,
  `apartament` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `imagini_produse`
--

CREATE TABLE `imagini_produse` (
  `imagine_id` int(11) NOT NULL,
  `imagine_cod` varchar(256) NOT NULL,
  `imagine_nume` varchar(256) NOT NULL DEFAULT 'default.png'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `imagini_produse`
--

INSERT INTO `imagini_produse` (`imagine_id`, `imagine_cod`, `imagine_nume`) VALUES
(1, 'afa25g5', 'proteinaON.jpg'),
(2, 'fawf2rg', 'default.png');

-- --------------------------------------------------------

--
-- Table structure for table `mesaje_contact_utilizatori`
--

CREATE TABLE `mesaje_contact_utilizatori` (
  `mesaj_id` int(11) NOT NULL,
  `nume` varchar(70) NOT NULL,
  `email` varchar(80) NOT NULL,
  `subiect` varchar(80) NOT NULL,
  `mesaj` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mesaje_contact_utilizatori`
--

INSERT INTO `mesaje_contact_utilizatori` (`mesaj_id`, `nume`, `email`, `subiect`, `mesaj`) VALUES
(5, 'Robert', 'robertgr991@yahoo.ro', 'Test', 'Mesaj de test.'),
(18, 'Bobi', 'bobipreda@gmail.com', 'Test', 'Teststaaw'),
(19, 'Robert', 'robertgr991@yahoo.ro', 'Test tr', 'afwag');

-- --------------------------------------------------------

--
-- Table structure for table `mesaje_utilizatori`
--

CREATE TABLE `mesaje_utilizatori` (
  `m_id` int(11) NOT NULL,
  `m_expeditor_uid` int(11) NOT NULL,
  `m_destinatar_uid` int(11) NOT NULL,
  `m_text` text NOT NULL,
  `m_data` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `m_titlu` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mesaje_utilizatori`
--

INSERT INTO `mesaje_utilizatori` (`m_id`, `m_expeditor_uid`, `m_destinatar_uid`, `m_text`, `m_data`, `m_titlu`) VALUES
(1, 6, 9, 'Testare trimitere mesaj privat.', '2018-05-05 14:08:03', 'Test mesaj privat'),
(2, 6, 9, 'gfdgdgr', '2018-05-05 14:37:56', 'fawfsfwa'),
(3, 6, 9, 'hdhfhdrhdf', '2018-05-05 14:41:11', 'Twefafsf'),
(4, 10, 6, 'Testarea mesajelor intre utilizatori', '2018-05-05 19:56:27', 'Test mesaj intre utilizatori'),
(5, 10, 6, 'Test2 mesaje', '2018-05-05 20:04:43', 'Test2'),
(6, 6, 10, 'gegsgsd', '2018-05-06 02:13:06', 'Test2352'),
(7, 6, 10, 'test cu blocare', '2018-05-06 12:19:29', 'Test cu blocare'),
(8, 6, 10, 'asfawfasg', '2018-05-06 20:23:57', 'RE: Test2'),
(9, 6, 10, 'shfdsherd', '2018-05-06 20:24:16', 'sgegseges'),
(10, 10, 6, 'sgdgesvsvds', '2018-05-06 20:28:41', 'RE: RE: Test2'),
(11, 6, 9, 'testg', '2018-05-10 02:04:19', 'testb');

-- --------------------------------------------------------

--
-- Table structure for table `pareri_articole`
--

CREATE TABLE `pareri_articole` (
  `a_id` int(11) NOT NULL,
  `u_id` int(11) NOT NULL,
  `parere` enum('l','d') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pareri_articole`
--

INSERT INTO `pareri_articole` (`a_id`, `u_id`, `parere`) VALUES
(1, 6, 'l'),
(1, 10, 'd'),
(3, 6, 'l');

-- --------------------------------------------------------

--
-- Table structure for table `pareri_comentarii_articole`
--

CREATE TABLE `pareri_comentarii_articole` (
  `c_id` int(11) NOT NULL,
  `u_id` int(11) NOT NULL,
  `parere` enum('l','d') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pareri_comentarii_articole`
--

INSERT INTO `pareri_comentarii_articole` (`c_id`, `u_id`, `parere`) VALUES
(33, 6, 'd'),
(34, 6, 'd');

-- --------------------------------------------------------

--
-- Table structure for table `pareri_comentarii_produse`
--

CREATE TABLE `pareri_comentarii_produse` (
  `c_id` int(11) NOT NULL,
  `u_id` int(11) NOT NULL,
  `parere` enum('l','d') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `producatori_produse`
--

CREATE TABLE `producatori_produse` (
  `producator_id` int(11) NOT NULL,
  `producator_nume` varchar(256) NOT NULL,
  `producator_nr_produse` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `producatori_produse`
--

INSERT INTO `producatori_produse` (`producator_id`, `producator_nume`, `producator_nr_produse`) VALUES
(1, 'Optimum Nutrition', 3),
(2, 'Myprotein', 7),
(4, 'MusclePharm', 9);

-- --------------------------------------------------------

--
-- Table structure for table `produse`
--

CREATE TABLE `produse` (
  `produs_id` int(11) NOT NULL,
  `produs_cod` varchar(256) NOT NULL,
  `produs_nume` varchar(256) NOT NULL,
  `produs_pret` int(11) NOT NULL,
  `produs_cantitate` int(11) NOT NULL,
  `produs_categorie_id` int(11) NOT NULL,
  `produs_descriere` varchar(200) NOT NULL,
  `produs_prezentare` text NOT NULL,
  `produs_producator_id` int(11) NOT NULL,
  `produs_img_id` int(11) NOT NULL DEFAULT '2'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `produse`
--

INSERT INTO `produse` (`produs_id`, `produs_cod`, `produs_nume`, `produs_pret`, `produs_cantitate`, `produs_categorie_id`, `produs_descriere`, `produs_prezentare`, `produs_producator_id`, `produs_img_id`) VALUES
(1, 'ef25g4b', 'Creatina ON 500gr.', 31, 30, 1, 'Creatina monohidrata ON', 'Creatina monohidrata de la Optimum Nutrition\r\n', 1, 2),
(3, 'ef25f2t4b', 'Proteina ON 500gr.', 15, 55, 2, 'Proteina din zer ON', 'Proteina de calitate din zer de la Optimum Nutrition', 1, 1),
(4, 'ef2rhgmn6', 'Gainer ON 2kg.', 65, 25, 3, 'Gainer ON', 'Gainer Optimum Nutrition cu 40% proteina!', 1, 2),
(5, 'fw25hg5', 'Creatina Myprotein 500gr.', 35, 26, 1, 'Creatina Myprotein', 'Creatina monohidrata Myprotein', 2, 2),
(6, 'ar235g', 'Creatina MuscleTech', 55, 1, 1, 'Creatina micronizata MuscleTech', 'Creatina micronizata de la MusclePharm cu absorbtie rapida', 4, 2);

--
-- Triggers `produse`
--
DELIMITER $$
CREATE TRIGGER `creste_nr_produse_categorie` AFTER INSERT ON `produse` FOR EACH ROW update categorii_produse
set categorie_nr_produse = (select count(*) from produse where produs_categorie_id = new.produs_categorie_id) 
where categorie_id = new.produs_categorie_id
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `creste_nr_produse_producator` AFTER INSERT ON `produse` FOR EACH ROW update producatori_produse
set producator_nr_produse = (select count(*) from produse where produs_producator_id = new.produs_producator_id) 
where producator_id = new.produs_producator_id
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `scade_nr_produse_categorie` AFTER DELETE ON `produse` FOR EACH ROW update categorii_produse
set categorie_nr_produse = (select count(*) from produse where produs_categorie_id = old.produs_categorie_id) 
where categorie_id = old.produs_categorie_id
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `scade_nr_produse_producator` AFTER DELETE ON `produse` FOR EACH ROW update producatori_produse
set producator_nr_produse = (select count(*) from produse where produs_producator_id = old.produs_producator_id) 
where producator_id = old.produs_producator_id
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `raportare_comentarii_articole`
--

CREATE TABLE `raportare_comentarii_articole` (
  `id` int(11) NOT NULL,
  `c_id` int(11) NOT NULL,
  `u_id` int(11) NOT NULL,
  `motiv` text NOT NULL,
  `data` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `raportare_comentarii_articole`
--

INSERT INTO `raportare_comentarii_articole` (`id`, `c_id`, `u_id`, `motiv`, `data`) VALUES
(1, 33, 6, 'testare', '2018-05-06 19:34:48'),
(2, 33, 6, 'limbaj', '2018-05-07 14:26:04'),
(3, 33, 6, 'test', '2018-05-10 02:22:00');

-- --------------------------------------------------------

--
-- Table structure for table `raportare_comentarii_produse`
--

CREATE TABLE `raportare_comentarii_produse` (
  `id` int(11) NOT NULL,
  `c_id` int(11) NOT NULL,
  `u_id` int(11) NOT NULL,
  `motiv` text NOT NULL,
  `data` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `raportare_comentarii_produse`
--

INSERT INTO `raportare_comentarii_produse` (`id`, `c_id`, `u_id`, `motiv`, `data`) VALUES
(1, 8, 6, 'Test2', '2018-05-06 19:42:27');

-- --------------------------------------------------------

--
-- Table structure for table `raportare_utilizatori`
--

CREATE TABLE `raportare_utilizatori` (
  `id` int(11) NOT NULL,
  `u_id` int(11) NOT NULL,
  `u_raportat_id` int(11) NOT NULL,
  `motiv` text NOT NULL,
  `data` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `raportare_utilizatori`
--

INSERT INTO `raportare_utilizatori` (`id`, `u_id`, `u_raportat_id`, `motiv`, `data`) VALUES
(1, 6, 10, 'Test', '2018-05-06 02:28:37');

-- --------------------------------------------------------

--
-- Table structure for table `utilizatori`
--

CREATE TABLE `utilizatori` (
  `u_id` int(11) NOT NULL,
  `u_email` varchar(256) NOT NULL,
  `u_username` varchar(256) NOT NULL,
  `u_password` varchar(70) NOT NULL,
  `u_rememberme` varchar(256) DEFAULT NULL,
  `u_data_inregistrare` datetime NOT NULL,
  `u_verificat` int(11) NOT NULL DEFAULT '0',
  `u_verificare_token` varchar(256) DEFAULT NULL,
  `u_cod_resetare_parola` varchar(256) DEFAULT NULL,
  `u_privilegiu_id` int(11) NOT NULL DEFAULT '1',
  `img_profil` varchar(256) DEFAULT NULL,
  `data_ultima_restrictie` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `utilizatori`
--

INSERT INTO `utilizatori` (`u_id`, `u_email`, `u_username`, `u_password`, `u_rememberme`, `u_data_inregistrare`, `u_verificat`, `u_verificare_token`, `u_cod_resetare_parola`, `u_privilegiu_id`, `img_profil`, `data_ultima_restrictie`) VALUES
(6, 'robertgr991@yahoo.ro', 'robert123', '$2y$10$83NnUVzMV8C9HZJ5vINgbeVBtIBSCA.m83butR0B0Q661ETukiKjS', NULL, '2018-05-02 18:12:16', 1, NULL, NULL, 1, 'F268IaLfmDzJZvEuo3cEBfsd6.jpg', NULL),
(9, 'bobipreda@gmail.com', 'bobi91251', '$2y$10$k6cqX5UJ3bLD/iJP//glIOc7rGu.ga3fHKNG4Mp3tG82EdZZgaWmq', NULL, '2018-05-04 14:06:31', 0, '10720e495c6e8788200af5930ff1522d91e594ed56d7128a598cc4fbe0cbe5c9', NULL, 1, NULL, NULL),
(10, 'abc@abc.com', 'test123', '$2y$10$l3z65pah4/gwevcN6wak..7DTw97.7h6kYyNbc1cC366xMK/FO6L6', NULL, '2018-05-05 19:54:47', 1, NULL, NULL, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `utilizatori_blocati`
--

CREATE TABLE `utilizatori_blocati` (
  `id` int(11) NOT NULL,
  `uid_i` int(11) NOT NULL,
  `uid_b` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `utilizatori_blocati`
--

INSERT INTO `utilizatori_blocati` (`id`, `uid_i`, `uid_b`) VALUES
(14, 6, 10);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `abonati_newsletter`
--
ALTER TABLE `abonati_newsletter`
  ADD PRIMARY KEY (`a_id`),
  ADD UNIQUE KEY `a_email` (`a_email`);

--
-- Indexes for table `articole`
--
ALTER TABLE `articole`
  ADD PRIMARY KEY (`a_id`),
  ADD UNIQUE KEY `a_titlu` (`a_titlu`),
  ADD KEY `a_categorie` (`a_categorie`);

--
-- Indexes for table `categorii_articole`
--
ALTER TABLE `categorii_articole`
  ADD PRIMARY KEY (`c_id`),
  ADD UNIQUE KEY `c_name` (`c_nume`);

--
-- Indexes for table `categorii_produse`
--
ALTER TABLE `categorii_produse`
  ADD PRIMARY KEY (`categorie_id`),
  ADD UNIQUE KEY `categorie_nume` (`categorie_nume`);

--
-- Indexes for table `comentarii_articole`
--
ALTER TABLE `comentarii_articole`
  ADD PRIMARY KEY (`c_id`),
  ADD KEY `comentarii_articole_ibfk_1` (`a_id`),
  ADD KEY `u_id` (`u_id`);

--
-- Indexes for table `comentarii_produse`
--
ALTER TABLE `comentarii_produse`
  ADD PRIMARY KEY (`c_id`),
  ADD KEY `comentarii_produse_ibfk_3` (`c_u_id`),
  ADD KEY `comentarii_produse_ibfk_1` (`c_p_id`);

--
-- Indexes for table `comenzi`
--
ALTER TABLE `comenzi`
  ADD PRIMARY KEY (`comanda_id`),
  ADD KEY `comenzi_ibfk_1` (`comanda_u_id`);

--
-- Indexes for table `comenzi_detalii`
--
ALTER TABLE `comenzi_detalii`
  ADD PRIMARY KEY (`cd_id`,`cd_c_id`,`cd_p_id`),
  ADD KEY `cd_c_id` (`cd_c_id`),
  ADD KEY `cd_p_id` (`cd_p_id`);

--
-- Indexes for table `date_utilizator_comanda`
--
ALTER TABLE `date_utilizator_comanda`
  ADD PRIMARY KEY (`id`,`u_id`),
  ADD KEY `comanda_id` (`comanda_id`),
  ADD KEY `date_utilizator_comanda_ibfk_3` (`u_id`);

--
-- Indexes for table `imagini_produse`
--
ALTER TABLE `imagini_produse`
  ADD PRIMARY KEY (`imagine_id`),
  ADD UNIQUE KEY `imagine_cod` (`imagine_cod`);

--
-- Indexes for table `mesaje_contact_utilizatori`
--
ALTER TABLE `mesaje_contact_utilizatori`
  ADD PRIMARY KEY (`mesaj_id`);

--
-- Indexes for table `mesaje_utilizatori`
--
ALTER TABLE `mesaje_utilizatori`
  ADD PRIMARY KEY (`m_id`),
  ADD KEY `m_expeditor_uid` (`m_expeditor_uid`),
  ADD KEY `m_destinatar_uid` (`m_destinatar_uid`);

--
-- Indexes for table `pareri_articole`
--
ALTER TABLE `pareri_articole`
  ADD PRIMARY KEY (`a_id`,`u_id`),
  ADD KEY `pareri_articole_ibfk_2` (`u_id`);

--
-- Indexes for table `pareri_comentarii_articole`
--
ALTER TABLE `pareri_comentarii_articole`
  ADD PRIMARY KEY (`c_id`,`u_id`),
  ADD KEY `pareri_comentarii_articole_ibfk_2` (`u_id`);

--
-- Indexes for table `pareri_comentarii_produse`
--
ALTER TABLE `pareri_comentarii_produse`
  ADD PRIMARY KEY (`c_id`,`u_id`),
  ADD KEY `pareri_comentarii_produse_ibfk_2` (`u_id`);

--
-- Indexes for table `producatori_produse`
--
ALTER TABLE `producatori_produse`
  ADD PRIMARY KEY (`producator_id`),
  ADD UNIQUE KEY `producator_nume` (`producator_nume`);

--
-- Indexes for table `produse`
--
ALTER TABLE `produse`
  ADD PRIMARY KEY (`produs_id`),
  ADD UNIQUE KEY `p_cod` (`produs_cod`),
  ADD KEY `produs_categorie_id` (`produs_categorie_id`),
  ADD KEY `produs_producator_id` (`produs_producator_id`),
  ADD KEY `produs_img_id` (`produs_img_id`),
  ADD KEY `produs_cod` (`produs_cod`);

--
-- Indexes for table `raportare_comentarii_articole`
--
ALTER TABLE `raportare_comentarii_articole`
  ADD PRIMARY KEY (`id`),
  ADD KEY `raportare_comentarii_articole_ibfk_1` (`u_id`);

--
-- Indexes for table `raportare_comentarii_produse`
--
ALTER TABLE `raportare_comentarii_produse`
  ADD PRIMARY KEY (`id`),
  ADD KEY `raportare_comentarii_produse_ibfk_1` (`u_id`);

--
-- Indexes for table `raportare_utilizatori`
--
ALTER TABLE `raportare_utilizatori`
  ADD PRIMARY KEY (`id`),
  ADD KEY `raportare_utilizatori_ibfk_1` (`u_id`),
  ADD KEY `raportare_utilizatori_ibfk_2` (`u_raportat_id`);

--
-- Indexes for table `utilizatori`
--
ALTER TABLE `utilizatori`
  ADD PRIMARY KEY (`u_id`,`u_email`,`u_username`),
  ADD UNIQUE KEY `u_username` (`u_username`);

--
-- Indexes for table `utilizatori_blocati`
--
ALTER TABLE `utilizatori_blocati`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilizatori_blocati_ibfk_1` (`uid_i`),
  ADD KEY `utilizatori_blocati_ibfk_2` (`uid_b`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `abonati_newsletter`
--
ALTER TABLE `abonati_newsletter`
  MODIFY `a_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `articole`
--
ALTER TABLE `articole`
  MODIFY `a_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `categorii_articole`
--
ALTER TABLE `categorii_articole`
  MODIFY `c_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `categorii_produse`
--
ALTER TABLE `categorii_produse`
  MODIFY `categorie_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `comentarii_articole`
--
ALTER TABLE `comentarii_articole`
  MODIFY `c_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `comentarii_produse`
--
ALTER TABLE `comentarii_produse`
  MODIFY `c_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `comenzi`
--
ALTER TABLE `comenzi`
  MODIFY `comanda_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `comenzi_detalii`
--
ALTER TABLE `comenzi_detalii`
  MODIFY `cd_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `date_utilizator_comanda`
--
ALTER TABLE `date_utilizator_comanda`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `imagini_produse`
--
ALTER TABLE `imagini_produse`
  MODIFY `imagine_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `mesaje_contact_utilizatori`
--
ALTER TABLE `mesaje_contact_utilizatori`
  MODIFY `mesaj_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `mesaje_utilizatori`
--
ALTER TABLE `mesaje_utilizatori`
  MODIFY `m_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `producatori_produse`
--
ALTER TABLE `producatori_produse`
  MODIFY `producator_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `produse`
--
ALTER TABLE `produse`
  MODIFY `produs_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `raportare_comentarii_articole`
--
ALTER TABLE `raportare_comentarii_articole`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `raportare_comentarii_produse`
--
ALTER TABLE `raportare_comentarii_produse`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `raportare_utilizatori`
--
ALTER TABLE `raportare_utilizatori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `utilizatori`
--
ALTER TABLE `utilizatori`
  MODIFY `u_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `utilizatori_blocati`
--
ALTER TABLE `utilizatori_blocati`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `articole`
--
ALTER TABLE `articole`
  ADD CONSTRAINT `articole_ibfk_1` FOREIGN KEY (`a_categorie`) REFERENCES `categorii_articole` (`c_id`);

--
-- Constraints for table `comentarii_articole`
--
ALTER TABLE `comentarii_articole`
  ADD CONSTRAINT `comentarii_articole_ibfk_1` FOREIGN KEY (`a_id`) REFERENCES `articole` (`a_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comentarii_articole_ibfk_2` FOREIGN KEY (`u_id`) REFERENCES `utilizatori` (`u_id`) ON DELETE CASCADE;

--
-- Constraints for table `comentarii_produse`
--
ALTER TABLE `comentarii_produse`
  ADD CONSTRAINT `comentarii_produse_ibfk_1` FOREIGN KEY (`c_p_id`) REFERENCES `produse` (`produs_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comentarii_produse_ibfk_3` FOREIGN KEY (`c_u_id`) REFERENCES `utilizatori` (`u_id`) ON DELETE CASCADE;

--
-- Constraints for table `comenzi`
--
ALTER TABLE `comenzi`
  ADD CONSTRAINT `comenzi_ibfk_1` FOREIGN KEY (`comanda_u_id`) REFERENCES `utilizatori` (`u_id`) ON DELETE NO ACTION;

--
-- Constraints for table `comenzi_detalii`
--
ALTER TABLE `comenzi_detalii`
  ADD CONSTRAINT `comenzi_detalii_ibfk_1` FOREIGN KEY (`cd_c_id`) REFERENCES `comenzi` (`comanda_id`),
  ADD CONSTRAINT `comenzi_detalii_ibfk_2` FOREIGN KEY (`cd_p_id`) REFERENCES `produse` (`produs_id`);

--
-- Constraints for table `date_utilizator_comanda`
--
ALTER TABLE `date_utilizator_comanda`
  ADD CONSTRAINT `date_utilizator_comanda_ibfk_1` FOREIGN KEY (`comanda_id`) REFERENCES `comenzi` (`comanda_id`),
  ADD CONSTRAINT `date_utilizator_comanda_ibfk_2` FOREIGN KEY (`comanda_id`) REFERENCES `comenzi` (`comanda_id`),
  ADD CONSTRAINT `date_utilizator_comanda_ibfk_3` FOREIGN KEY (`u_id`) REFERENCES `utilizatori` (`u_id`) ON DELETE CASCADE;

--
-- Constraints for table `mesaje_utilizatori`
--
ALTER TABLE `mesaje_utilizatori`
  ADD CONSTRAINT `mesaje_utilizatori_ibfk_1` FOREIGN KEY (`m_expeditor_uid`) REFERENCES `utilizatori` (`u_id`) ON DELETE NO ACTION,
  ADD CONSTRAINT `mesaje_utilizatori_ibfk_2` FOREIGN KEY (`m_destinatar_uid`) REFERENCES `utilizatori` (`u_id`) ON DELETE NO ACTION;

--
-- Constraints for table `pareri_articole`
--
ALTER TABLE `pareri_articole`
  ADD CONSTRAINT `pareri_articole_ibfk_1` FOREIGN KEY (`a_id`) REFERENCES `articole` (`a_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pareri_articole_ibfk_2` FOREIGN KEY (`u_id`) REFERENCES `utilizatori` (`u_id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `pareri_comentarii_articole`
--
ALTER TABLE `pareri_comentarii_articole`
  ADD CONSTRAINT `pareri_comentarii_articole_ibfk_1` FOREIGN KEY (`c_id`) REFERENCES `comentarii_articole` (`c_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pareri_comentarii_articole_ibfk_2` FOREIGN KEY (`u_id`) REFERENCES `utilizatori` (`u_id`) ON DELETE CASCADE;

--
-- Constraints for table `pareri_comentarii_produse`
--
ALTER TABLE `pareri_comentarii_produse`
  ADD CONSTRAINT `pareri_comentarii_produse_ibfk_1` FOREIGN KEY (`c_id`) REFERENCES `comentarii_produse` (`c_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pareri_comentarii_produse_ibfk_2` FOREIGN KEY (`u_id`) REFERENCES `utilizatori` (`u_id`) ON DELETE CASCADE;

--
-- Constraints for table `produse`
--
ALTER TABLE `produse`
  ADD CONSTRAINT `produse_ibfk_1` FOREIGN KEY (`produs_categorie_id`) REFERENCES `categorii_produse` (`categorie_id`),
  ADD CONSTRAINT `produse_ibfk_2` FOREIGN KEY (`produs_producator_id`) REFERENCES `producatori_produse` (`producator_id`),
  ADD CONSTRAINT `produse_ibfk_3` FOREIGN KEY (`produs_img_id`) REFERENCES `imagini_produse` (`imagine_id`);

--
-- Constraints for table `raportare_comentarii_articole`
--
ALTER TABLE `raportare_comentarii_articole`
  ADD CONSTRAINT `raportare_comentarii_articole_ibfk_1` FOREIGN KEY (`u_id`) REFERENCES `utilizatori` (`u_id`) ON DELETE NO ACTION;

--
-- Constraints for table `raportare_comentarii_produse`
--
ALTER TABLE `raportare_comentarii_produse`
  ADD CONSTRAINT `raportare_comentarii_produse_ibfk_1` FOREIGN KEY (`u_id`) REFERENCES `utilizatori` (`u_id`) ON DELETE NO ACTION;

--
-- Constraints for table `raportare_utilizatori`
--
ALTER TABLE `raportare_utilizatori`
  ADD CONSTRAINT `raportare_utilizatori_ibfk_1` FOREIGN KEY (`u_id`) REFERENCES `utilizatori` (`u_id`) ON DELETE NO ACTION,
  ADD CONSTRAINT `raportare_utilizatori_ibfk_2` FOREIGN KEY (`u_raportat_id`) REFERENCES `utilizatori` (`u_id`) ON DELETE NO ACTION;

--
-- Constraints for table `utilizatori_blocati`
--
ALTER TABLE `utilizatori_blocati`
  ADD CONSTRAINT `utilizatori_blocati_ibfk_1` FOREIGN KEY (`uid_i`) REFERENCES `utilizatori` (`u_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `utilizatori_blocati_ibfk_2` FOREIGN KEY (`uid_b`) REFERENCES `utilizatori` (`u_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
