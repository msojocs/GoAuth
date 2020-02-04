-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- 主机： localhost:3306
-- 生成日期： 2020-02-04 10:03:27
-- 服务器版本： 5.6.45
-- PHP 版本： 7.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `jysafec2_wp`
--

-- --------------------------------------------------------

--
-- 表的结构 `demo_wechat`
--

CREATE TABLE `demo_wechat` (
  `id` int(20) NOT NULL,
  `sk` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `userinfo` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- 转存表中的数据 `demo_wechat`
--

INSERT INTO `demo_wechat` (`id`, `sk`, `userinfo`, `status`) VALUES
(38, '1580722429', NULL, 0),
(39, '1580723345', NULL, 0),
(40, '1580725753', NULL, 0),
(41, '1580725774', NULL, 0),
(42, '1580725822', NULL, 0),
(43, '1580725824', NULL, 0),
(44, '1580725890', NULL, 0),
(45, '1580725908', NULL, 0),
(46, '1580725920', NULL, 0),
(48, '1580738459', NULL, 0),
(50, '1580738630', NULL, 0),
(51, '1580738667', NULL, 0),
(52, '1580738714', '{\"nickName\":\"祭夜\",\"gender\":0,\"language\":\"zh_CN\",\"city\":\"\",\"province\":\"\",\"country\":\"\",\"avatarUrl\":\"https://wx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTKYymoZiaibn7gRfvEicRp4SKJ8pBkwELYsrzEn59VTVqAwFQ7GVZbxJcfoicJIiahBWiaLHToBs3kOPJZA/132\",\"openid\":\"ojtup5flrxgaN_ZNE-Pwmlk4al0Q\",\"unionid\":null}', 0),
(53, '1580741340', '{\"nickName\":\"bouché\",\"gender\":1,\"language\":\"zh_CN\",\"city\":\"Jiujiang\",\"province\":\"Jiangxi\",\"country\":\"China\",\"avatarUrl\":\"https://wx.qlogo.cn/mmopen/vi_32/j8nEmHoYcdcrC7GPzma4x99mtC5Byxjhic2YlNZhglxKXkXdMStZ6HHr3PyUnP8VRM5TLGjJn2EVmRgOBenSic5Q/132\",\"openid\":\"ojtup5czUV0TWbbq0q7rnVav2Tn0\",\"unionid\":null}', 0),
(55, '1580742359', '{\"nickName\":\"祭夜\",\"gender\":0,\"language\":\"zh_CN\",\"city\":\"\",\"province\":\"\",\"country\":\"\",\"avatarUrl\":\"https://wx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTKYymoZiaibn7gRfvEicRp4SKJ8pBkwELYsrzEn59VTVqAwFQ7GVZbxJcfoicJIiahBWiaLHToBs3kOPJZA/132\",\"openid\":\"ojtup5flrxgaN_ZNE-Pwmlk4al0Q\",\"unionid\":null}', 0),
(56, '1580743723', NULL, 0),
(57, '1580744673', NULL, 0);

--
-- 转储表的索引
--

--
-- 表的索引 `demo_wechat`
--
ALTER TABLE `demo_wechat`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sk_2` (`sk`),
  ADD KEY `sk` (`sk`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `demo_wechat`
--
ALTER TABLE `demo_wechat`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
