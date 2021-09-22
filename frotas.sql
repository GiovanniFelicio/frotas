-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 09-Jan-2020 às 19:41
-- Versão do servidor: 5.7.28-0ubuntu0.16.04.2
-- PHP Version: 7.0.33-13+ubuntu16.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `frotas`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `auth_func_vehicle`
--

CREATE TABLE `auth_func_vehicle` (
  `id` int(11) UNSIGNED NOT NULL,
  `func_id` int(11) UNSIGNED NOT NULL,
  `sec_id` int(11) UNSIGNED NOT NULL,
  `vehicle_id` int(11) UNSIGNED NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `gastos_veiculos`
--

CREATE TABLE `gastos_veiculos` (
  `id` int(10) UNSIGNED NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `sec_id` int(11) NOT NULL,
  `item` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `valor` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `logbook`
--

CREATE TABLE `logbook` (
  `id` int(10) UNSIGNED NOT NULL,
  `dateTimeSai` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dateTimeCheg` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `veiculo` int(11) NOT NULL,
  `func_id` int(11) NOT NULL,
  `setor_id` int(11) NOT NULL,
  `sec_id` int(11) NOT NULL,
  `solic_id` int(11) NOT NULL,
  `origem` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kmInicial` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `destino` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kmFinal` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `irreguSai` text COLLATE utf8mb4_unicode_ci,
  `irreguCheg` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(5, '2019_10_13_011108_create_employees_table', 4),
(39, '2019_10_14_161945_create_auth_portaria_table', 5),
(72, '2019_10_15_185805_create_employees_table', 6),
(89, '2014_10_12_000000_create_users_table', 7),
(90, '2014_10_12_100000_create_password_resets_table', 7),
(91, '2019_09_16_131255_create_ultimo_acesso_table', 7),
(92, '2019_10_10_135514_create_portarias_table', 7),
(93, '2019_10_13_015040_create_setores_table', 7),
(94, '2019_10_14_162227_create_veiculos_table', 7),
(95, '2019_10_16_134341_create_empresas_table', 7),
(96, '2019_10_16_140416_create_auth_emp_users_table', 7),
(97, '2019_10_16_140935_create_auth_port_func_table', 7),
(98, '2019_10_20_160452_create_vehicle_port_table', 7),
(99, '2019_10_25_002305_create_users_actions_table', 7),
(100, '2019_10_26_142550_create_msg_logs_table', 8),
(101, '2019_10_29_140740_create_logbook_table', 9),
(102, '2019_11_09_175850_create_secretarias_table', 10),
(103, '2019_11_11_101837_create__auth_func_vehicle_table', 11),
(104, '2019_11_11_102437_create_auth_func_vehicle_table', 12),
(105, '2019_11_19_164717_create_reservas_table', 13),
(106, '2019_11_19_170905_create_agenda_table', 14),
(107, '2019_11_20_111045_create_passageiros_table', 15),
(108, '2019_11_22_100322_create_notifications_table', 16),
(109, '2019_11_23_150552_create_solicitations_table', 17),
(110, '2019_12_04_172849_create_suggestions_table', 18),
(111, '2019_12_07_221107_create_gastos_veiculo_table', 19),
(112, '2019_12_07_221652_create_gastos_veiculos_table', 20);

-- --------------------------------------------------------

--
-- Estrutura da tabela `notifications`
--

CREATE TABLE `notifications` (
  `id` int(10) UNSIGNED NOT NULL,
  `func_id` int(11) NOT NULL,
  `sec_id` int(11) NOT NULL,
  `mensagem` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `destino` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `passageiros`
--

CREATE TABLE `passageiros` (
  `id` int(10) UNSIGNED NOT NULL,
  `func_id` int(11) UNSIGNED NOT NULL,
  `demanda_id` int(11) UNSIGNED NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `secretarias`
--

CREATE TABLE `secretarias` (
  `id` int(10) UNSIGNED NOT NULL,
  `nameSec` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `emailSec` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `secretarias`
--

INSERT INTO `secretarias` (`id`, `nameSec`, `emailSec`, `created_at`, `updated_at`) VALUES
(1, 'FUNDETEC', 'fundetec@fundetec.org.br', '2019-11-09 03:00:00', NULL),
(2, 'SEPLAG', 'seplag@cascavel.pr.gov.br', '2019-11-09 21:36:34', '2019-11-09 21:36:34');

-- --------------------------------------------------------

--
-- Estrutura da tabela `setores`
--

CREATE TABLE `setores` (
  `id` int(11) UNSIGNED NOT NULL,
  `sec_id` int(11) UNSIGNED NOT NULL,
  `nameSector` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `setores`
--

INSERT INTO `setores` (`id`, `sec_id`, `nameSector`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Fábrica de Inovação', 1, '2019-11-12 03:28:53', '2019-11-12 03:28:53'),
(2, 1, 'Diretoria Técnica', 1, '2019-11-30 23:29:16', '2019-11-30 23:29:16'),
(3, 1, 'Gerência Administrativa', 1, '2019-11-30 23:32:14', '2019-11-30 23:38:08');

-- --------------------------------------------------------

--
-- Estrutura da tabela `solicitations`
--

CREATE TABLE `solicitations` (
  `id` int(11) UNSIGNED NOT NULL,
  `func_id` int(11) NOT NULL,
  `sec_id` int(11) NOT NULL,
  `mensagem` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `origem` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `destino` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `horas` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` tinyint(1) NOT NULL,
  `status` tinyint(2) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `suggestions`
--

CREATE TABLE `suggestions` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sec_id` int(11) NOT NULL,
  `grupo` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `suggestions`
--

INSERT INTO `suggestions` (`id`, `name`, `sec_id`, `grupo`, `created_at`, `updated_at`) VALUES
(1, 'PMC', 1, 0, '2019-12-04 03:00:00', NULL),
(2, 'Diss Interlagos', 1, 0, '2019-12-04 03:00:00', NULL),
(3, 'Sesau', 1, 0, '2019-12-04 03:00:00', NULL),
(4, 'Sesop', 1, 0, '2019-12-04 03:00:00', NULL),
(5, 'Combustível', 1, 1, '2019-12-05 03:00:00', NULL),
(6, 'Revisão', 1, 1, '2019-12-05 03:00:00', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `ultimo_acesso`
--

CREATE TABLE `ultimo_acesso` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_login` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_login_ip` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `setor_id` int(11) NOT NULL,
  `sec_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `level` tinyint(2) NOT NULL,
  `token_access` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `matricula` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `authSpecial` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `users`
--

INSERT INTO `users` (`id`, `setor_id`, `sec_id`, `name`, `email`, `password`, `level`, `token_access`, `matricula`, `authSpecial`, `status`, `remember_token`, `created_at`, `updated_at`) VALUES
(2, 3, 1, 'Marcio Filipe', 'marciof@fundetec.org.br', '$2y$12$2eP5AbzRnBKeKSYi3B5zM.T6stzct1kTq.GrH4TlybIIw8N3BK8cS', 3, '$2y$10$1vMKTmIUiEQIDYDfiTq4AelKcGsFyZo7S6ODUtwUneKqMAU333Gtu', '241169', 0, 1, 'voCzQ0BMa3Yf02vxofLBjXlC3abBlEJ121vNExECanbXAdzKtkNmmNAYFUM2', '2019-11-05 20:52:19', '2019-12-07 17:01:29'),
(5, 2, 1, 'Felipe Kuhn', 'felipe.kuhn@fundetec.org.br', '$2y$10$sa200a6vdte5SGvg8mHQUeMNPpYKvm/GsRqIwlUTtOK9xy.gxz1Xm', 1, '$2y$10$49AIyF1qEm0/sJEofDWtuejdIgUjm3gbzZlf7oCO63Mat7kpl.1ym', '39703', 0, 1, '1t92wBN5koyzBtDGQ38cpXXxPAe4C3SXbTm24RVqTiyXw2P4dcBxionS6FoA', '2019-11-11 04:38:39', '2019-12-06 13:04:03'),
(6, 1, 1, 'Aline', 'aline@fundetec.org.br', '$2y$10$0yiqTCJzwUwkeotx7PVIwOkZsVDPkquehm5LudZy8wYDAHnfY6/fC', 2, NULL, '124554', 0, 1, NULL, '2019-11-12 03:31:54', '2019-12-27 23:09:45'),
(7, 1, 1, 'Eng Carvalho', 'giovanni.carvalho@fundetec.org.br', '$2y$12$vi9jtm.BwIsev1wEdS/vNeKfAZcd7ku8JZKu.hbsjXMWEAbHMIgyC', 5, '$2y$10$2ZXiTGBBShe4AEBmDSuH3ueWa4fJm3Bc7iD9oI0uLbItsRUCQy0ru', '17.171-7', 0, 1, '', '2019-11-19 22:37:50', '2020-01-09 15:02:57'),
(8, 1, 1, 'Samuel Pedro', 'samuel@fundetec.org.br', '$2y$10$2XANOYCBzxUACb4KmHTeSO/Ft.fo44w7jCEpMGw1pRMWRESGELLuO', 1, NULL, '12547', 0, 1, NULL, '2019-11-20 20:16:38', '2019-11-30 22:46:49'),
(9, 0, 1, 'Teste', 'teste@teste.com', '$2y$10$S8b9s5acmfOuTcvRl8yLiu9k8jMsGzYoS9P12hRBvZhIDJCvRPQfu', 1, NULL, '17.17147', 0, 0, NULL, '2019-11-20 20:17:02', '2019-11-30 23:28:55'),
(10, 2, 1, 'Fabrício Barbi', 'fabricio.barbi@fundetec.org.br', '$2y$10$7U5ZX8QTYLptiqRNYE543.22hCTXMcThSeTdcpL0bVD55gdCBLaji', 3, NULL, '00000', 0, 1, NULL, '2019-11-29 21:00:44', '2019-11-30 23:31:45'),
(11, 0, 1, 'Francieli Donato', 'francieli@fundetec.org.br', '$2y$10$XC0BwkJRmDVmPNQIJFTtc.VZK4F/YyHPRPiEu/nxRt5oonXfoGqIm', 2, NULL, '111111', 0, 1, NULL, '2019-12-27 21:25:38', '2019-12-27 21:25:38');

-- --------------------------------------------------------

--
-- Estrutura da tabela `users_actions`
--

CREATE TABLE `users_actions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `func` int(11) NOT NULL,
  `sec_id` int(11) NOT NULL,
  `setor_id` int(11) NOT NULL,
  `action` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `veiculos`
--

CREATE TABLE `veiculos` (
  `id` int(10) UNSIGNED NOT NULL,
  `sec_id` int(11) UNSIGNED NOT NULL,
  `nameVei` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `placa` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `number` mediumint(9) NOT NULL,
  `km` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `auth_func_vehicle`
--
ALTER TABLE `auth_func_vehicle`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gastos_veiculos`
--
ALTER TABLE `gastos_veiculos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logbook`
--
ALTER TABLE `logbook`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `passageiros`
--
ALTER TABLE `passageiros`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `secretarias`
--
ALTER TABLE `secretarias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `secretarias_emailsec_unique` (`emailSec`);

--
-- Indexes for table `setores`
--
ALTER TABLE `setores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `emp_id` (`sec_id`);

--
-- Indexes for table `solicitations`
--
ALTER TABLE `solicitations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `suggestions`
--
ALTER TABLE `suggestions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ultimo_acesso`
--
ALTER TABLE `ultimo_acesso`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `users_actions`
--
ALTER TABLE `users_actions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `veiculos`
--
ALTER TABLE `veiculos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `emp_id` (`sec_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `auth_func_vehicle`
--
ALTER TABLE `auth_func_vehicle`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `gastos_veiculos`
--
ALTER TABLE `gastos_veiculos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `logbook`
--
ALTER TABLE `logbook`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;
--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `passageiros`
--
ALTER TABLE `passageiros`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `secretarias`
--
ALTER TABLE `secretarias`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `setores`
--
ALTER TABLE `setores`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `solicitations`
--
ALTER TABLE `solicitations`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `suggestions`
--
ALTER TABLE `suggestions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `ultimo_acesso`
--
ALTER TABLE `ultimo_acesso`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `users_actions`
--
ALTER TABLE `users_actions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `veiculos`
--
ALTER TABLE `veiculos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `setores`
--
ALTER TABLE `setores`
  ADD CONSTRAINT `setores_ibfk_1` FOREIGN KEY (`sec_id`) REFERENCES `secretarias` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `veiculos`
--
ALTER TABLE `veiculos`
  ADD CONSTRAINT `veiculos_ibfk_1` FOREIGN KEY (`sec_id`) REFERENCES `secretarias` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
