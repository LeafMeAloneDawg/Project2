-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: mariadb
-- Generation Time: Oct 23, 2026 at 04:34 AM
-- Server version: 12.3.3-MariaDB-ubu2404
-- PHP Version: 8.3.35

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project2_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `eoi`
--

CREATE TABLE `eoi` (
  `EOInumber` int(11) NOT NULL,
  `job_reference_number` varchar(6) NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `date_of_birth` date NOT NULL,
  `gender` varchar(20) NOT NULL,
  `street_address` varchar(40) NOT NULL,
  `suburb` varchar(40) NOT NULL,
  `state` varchar(30) NOT NULL,
  `postcode` char(4) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(12) NOT NULL,
  `skills` varchar(255) DEFAULT NULL,
  `other_skills` text DEFAULT NULL,
  `status` enum('New','Current','Final') NOT NULL DEFAULT 'New'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `eoi`
--

INSERT INTO `eoi` (`EOInumber`, `job_reference_number`, `first_name`, `last_name`, `date_of_birth`, `gender`, `street_address`, `suburb`, `state`, `postcode`, `email`, `phone`, `skills`, `other_skills`, `status`) VALUES
(1, 'CSW214', 'Jeffery', 'Poink', '2004-10-19', 'male', '123 Bourke St', 'Melbourne', 'victoria', '3000', 'JeffP@gmail.com', '0400476134', 'communication, teamwork, leadership', 'Organisation', 'New'),
(2, 'VEC091', 'Juliane', 'Lianas', '1998-02-14', 'female', 'Unit 1/333 Church St', 'Richmond', 'victoria', '3121', 'JulianeLianas@gmail.com', '0454988422', 'communication, teamwork, leadership, technical', 'I can do anything.', 'New'),
(3, 'YOW338', 'Clay', 'Junction', '1988-09-29', 'male', '574 Chapel St', 'South Yarra', 'victoria', '3141', 'CJunction@gmail.com', '0456118234', 'communication, technical, problem-solving', 'Knowledge of the foster system and how to help at-risk kids.', 'New'),
(4, 'CSW214', 'Paul', 'Hinkey', '1980-03-01', 'male', '133 Hope St', 'Brunswick', 'victoria', '3056', 'PHinkey@gmail.com', '0456388244', 'communication, teamwork, time-management', 'Can fly a plane', 'New');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `job_reference_number` varchar(6) NOT NULL,
  `title` varchar(100) NOT NULL,
  `short_description` text NOT NULL,
  `salary` varchar(100) NOT NULL,
  `reports_to` varchar(100) NOT NULL,
  `responsibilities` text NOT NULL,
  `essential_requirements` text NOT NULL,
  `preferable_requirements` text NOT NULL,
  `closing_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`job_reference_number`, `title`, `short_description`, `salary`, `reports_to`, `responsibilities`, `essential_requirements`, `preferable_requirements`, `closing_date`) VALUES
('CSW214', 'Community Support Worker', 'Provide direct, practical support to young people and families accessing our community programs, helping them connect with local services and build long-term wellbeing.', '$68,000 – $74,000 per annum (pro rata for part-time)', 'Community Programs Manager', 'Complete intake interviews and assess each client\'s needs.\nDevelop and review individual support plans with clients.\nCoordinate referrals to housing, health and education services.\nMaintain accurate, confidential case notes and records.\nAttend weekly team case-review meetings.', 'Diploma of Community Services or a related qualification.\nCurrent Working with Children Check and National Police Check.\nAt least one year of experience in a frontline support role.\nCurrent Victorian driver\'s licence.', 'Experience supporting culturally and linguistically diverse communities.\nCurrent First Aid and CPR certificate.\nKnowledge of Melbourne\'s youth and family service system.', '2026-10-25'),
('VEC091', 'Volunteer Engagement Coordinator', 'Recruit, train and support our volunteer network so that volunteers are well matched to programs and feel valued in their contribution to the community.', '$72,000 – $80,000 per annum', 'Operations Manager', 'Recruit and onboard new volunteers across all programs.\nPlan and deliver regular volunteer induction and training sessions.\nBuild volunteer rosters for community events and outreach.\nCheck in with volunteers and resolve issues as they arise.\nOrganise volunteer recognition and appreciation events.', 'Experience coordinating volunteers, teams or community groups.\nStrong written and verbal communication skills.\nCurrent Working with Children Check.\nConfident using rostering or database software.', 'Qualification in community development, human resources or event management.\nExperience with volunteer-management platforms such as Better Impact.\nAvailability for occasional evening and weekend events.', '2026-10-25'),
('YOW338', 'Youth Outreach Worker', 'Work alongside young people aged 12–25 in their own communities, running outreach activities and mentoring that build confidence, connection and trust.', '$65,000 – $70,000 per annum (pro rata for part-time)', 'Youth Services Team Leader', 'Deliver after-school, school-holiday and drop-in youth programs.\nBuild trusted relationships with young people and their families.\nRecognise early signs of disengagement and respond appropriately.\nRefer young people to specialist support services when needed.\nRecord session attendance and program outcomes.', 'Certificate IV in Youth Work or a related qualification.\nCurrent Working with Children Check and National Police Check.\nAvailability for after-school, evening and some weekend shifts.\nCurrent Victorian driver\'s licence.', 'Lived experience of, or a strong connection to, the local community.\nExperience facilitating group activities or workshops.\nTraining in Youth Mental Health First Aid.', '2026-10-25');

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `student_id` varchar(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `fun_fact_1` text NOT NULL,
  `fun_fact_2` text NOT NULL,
  `fun_fact_3` text NOT NULL,
  `quote` text NOT NULL,
  `quote_translated` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`student_id`, `name`, `fun_fact_1`, `fun_fact_2`, `fun_fact_3`, `quote`, `quote_translated`) VALUES
('103839016', 'Aquib', 'Plays guitar.', 'Enjoys live rock/metal concerts.', 'Loves to collect band official merchandise.', 'কখনো হাল ছাড়বে না', 'Never give up '),
('103990515', 'Xander', 'Lived in Thailand for a year.', 'Collects physical media.', 'Enjoys surfing.', 'ผมรักแม่ของผม', 'I love my mum'),
('105641802', 'Mason', 'My bedroom was a 1940s double-decker bus as a teen.', 'I live with my best friend from year 7.', 'I learnt to weld to fix my the love of my life (A 1984 Celica), blew her up three times and then sold it.', 'पेड़ मेरे पसंदीदा जानवर हैं।', 'Trees are my favorite animal.'),
('106893099', 'Ryan', 'Has a pet dog named Fuji.', 'Has an interest in old Toyotas.', 'Enjoys CAD design and 3d printing.', '胸を張って生きろ。己の弱さや不甲斐なさにどれだけ打ちのめされようと、心を燃やせ。', 'Go ahead and live with your head held high! No matter how devastated you may be by your own weakness or uselessness, set your heart ablaze!');

-- --------------------------------------------------------

--
-- Table structure for table `member_contributions`
--

CREATE TABLE `member_contributions` (
  `contribution_id` int(11) NOT NULL,
  `student_id` varchar(10) NOT NULL,
  `project_part` varchar(25) NOT NULL,
  `task_description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `member_contributions`
--

INSERT INTO `member_contributions` (`contribution_id`, `student_id`, `project_part`, `task_description`) VALUES
(1, '103839016', '1', 'Job Page Html'),
(2, '103839016', '1', 'Job Page CSS'),
(3, '103839016', '1', 'Shared responsibility on Jira'),
(4, '103839016', '1 & 2', 'Shared responsibility making regular commits to Github'),
(5, '103839016', '1 & 2', 'Shared responsibility joining group meetings, in class and online'),
(6, '103839016', '1 & 2', 'Shared responsibility communicating in timely manner to group chats'),
(7, '103839016', '1', 'Shared responsibility combining CSS sheets into one style.css'),
(8, '103839016', '2', 'Create jobs table'),
(9, '103839016', '2', 'Created login page'),
(10, '103839016', '2', 'Created search.php'),
(11, '103839016', '2', 'Populate jobs table from database'),
(12, '103839016', '2', 'Implement jobs table creation if not exit'),
(13, '103839016', '2', 'Implemented secure session setup and login'),
(14, '103839016', '2', 'Implemented redirection of logged in users'),
(15, '103839016', '2', 'Implemented login attempt limit'),
(16, '103839016', '2', 'Implemented login link in footer'),
(17, '103839016', '2', 'Implemented search.php into jobs.php'),
(18, '105641802', '1', 'Home Page Html'),
(19, '105641802', '1', 'Home Page CSS'),
(20, '105641802', '1', 'Shared responsibility on Jira'),
(21, '105641802', '1 & 2', 'Shared responsibility making regular commits to Github'),
(22, '105641802', '1 & 2', 'Shared responsibility joining group meetings, in class and online'),
(23, '105641802', '1 & 2', 'Shared responsibility communicating in timely manner to group chats'),
(24, '105641802', '1', 'Shared responsibility combining CSS sheets into one style.css'),
(25, '105641802', '2', 'Duplicated project from part 1'),
(26, '105641802', '2', 'Created db_connect.php'),
(27, '105641802', '2', 'Created manage.php'),
(28, '105641802', '2', 'Implement manage.php sorting functions'),
(29, '105641802', '2', 'Implemented manage.php searching functions'),
(30, '105641802', '2', 'Styled and formatted manage.php page'),
(31, '106893099', '1', 'About Page Html'),
(32, '106893099', '1', 'About Page CSS'),
(33, '106893099', '1', 'Shared responsibility on Jira'),
(34, '106893099', '1 & 2', 'Shared responsibility making regular commits to Github'),
(35, '106893099', '1 & 2', 'Shared responsibility joining group meetings, in class and online'),
(36, '106893099', '1 & 2', 'Shared responsibility communicating in timely manner to group chats'),
(37, '106893099', '1', 'Shared responsibility combining CSS sheets into one style.css'),
(38, '106893099', '2', 'Converted pages to .php rather than .html'),
(39, '106893099', '2', 'Created members table'),
(40, '106893099', '2', 'Created member_contribution table'),
(41, '106893099', '2', 'Cleaned up header.inc, nav.inc and footer.inc files'),
(42, '106893099', '2', 'Fixed skip link for all pages'),
(43, '106893099', '2', 'Fixed photo caption size in about.php'),
(44, '106893099', '2', 'Fixed navigation bar underline for current page'),
(45, '103990515', '1', 'Apply Page Html'),
(46, '103990515', '1', 'Apply Page CSS'),
(47, '103990515', '1', 'Shared responsibility on Jira'),
(48, '103990515', '1 & 2', 'Shared responsibility making regular commits to Github'),
(49, '103990515', '1 & 2', 'Shared responsibility joining group meetings, in class and online'),
(50, '103990515', '1 & 2', 'Shared responsibility communicating in timely manner to group chats'),
(51, '103990515', '1', 'Shared responsibility combining CSS sheets into one style.css'),
(52, '103990515', '2', 'Created settings.php'),
(53, '103990515', '2', 'Created EOI.php'),
(54, '103990515', '2', 'Created EOI table'),
(55, '103990515', '2', 'Implemented form validation error handling'),
(56, '103990515', '2', 'Sanitized form input data to ensure secure database insertion and prevent SQL injection');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `failed_attempts` int(11) NOT NULL DEFAULT 0,
  `locked_until` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password_hash`, `failed_attempts`, `locked_until`) VALUES
(1, 'admin', '$2y$12$BFI1ESGCfqaOlQUlpQhP1uWYfPQgCZ932BCTzdzf9l2N2e8G0zw3W', 0, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `eoi`
--
ALTER TABLE `eoi`
  ADD PRIMARY KEY (`EOInumber`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`job_reference_number`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`student_id`);

--
-- Indexes for table `member_contributions`
--
ALTER TABLE `member_contributions`
  ADD PRIMARY KEY (`contribution_id`),
  ADD KEY `foreign_key_member` (`student_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `eoi`
--
ALTER TABLE `eoi`
  MODIFY `EOInumber` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `member_contributions`
--
ALTER TABLE `member_contributions`
  MODIFY `contribution_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `member_contributions`
--
ALTER TABLE `member_contributions`
  ADD CONSTRAINT `foreign_key_member` FOREIGN KEY (`student_id`) REFERENCES `members` (`student_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
