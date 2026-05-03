-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 03, 2026 at 01:50 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bethel_school`
--

-- --------------------------------------------------------

--
-- Table structure for table `about_content`
--

CREATE TABLE `about_content` (
  `id` int(11) NOT NULL,
  `section` varchar(50) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `icon_class` varchar(50) DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `about_content`
--

INSERT INTO `about_content` (`id`, `section`, `title`, `content`, `icon_class`, `display_order`, `status`, `updated_at`) VALUES
(1, 'mission', 'Our Mission', 'To provide holistic, internationally-competitive education that nurtures students\' intellectual, spiritual, and social development while preserving Filipino values and cultural heritage.', NULL, 1, 'active', '2026-05-03 11:22:48'),
(2, 'vision', 'Our Vision', 'To be a leading Christian educational institution in the Visayas, producing globally competitive, values-driven leaders who soar like eagles in their chosen fields.', NULL, 2, 'active', '2026-05-03 11:22:48'),
(3, 'history', 'Our Story', 'Founded in 2001, Bethel International School began with a simple yet powerful vision: to provide quality education that combines international standards with Filipino values. Located in the peaceful community of Pawing, Palo, Leyte, our school has grown from humble beginnings to become one of the region\'s most respected educational institutions.', NULL, 3, 'active', '2026-05-03 11:22:48');

-- --------------------------------------------------------

--
-- Table structure for table `about_stats`
--

CREATE TABLE `about_stats` (
  `id` int(11) NOT NULL,
  `stat_number` varchar(20) NOT NULL,
  `stat_label` varchar(100) NOT NULL,
  `display_order` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `about_stats`
--

INSERT INTO `about_stats` (`id`, `stat_number`, `stat_label`, `display_order`, `status`) VALUES
(1, '20+', 'Years of Excellence', 1, 'active'),
(2, '300+', 'Students Enrolled', 2, 'active'),
(3, '100+', 'Qualified Faculty', 3, 'active'),
(4, '98%', 'Graduation Rate', 4, 'active'),
(0, '20+', 'Years of Excellence', 1, 'active'),
(0, '1,500+', 'Students Enrolled', 2, 'active'),
(0, '100+', 'Qualified Faculty', 3, 'active'),
(0, '98%', 'Graduation Rate', 4, 'active');

-- --------------------------------------------------------

--
-- Table structure for table `academic_calendar`
--

CREATE TABLE `academic_calendar` (
  `id` int(11) NOT NULL,
  `event_name` varchar(200) NOT NULL,
  `event_date` date NOT NULL,
  `description` text DEFAULT NULL,
  `event_type` enum('regular','holiday','exam','event','deadline') DEFAULT 'regular',
  `display_order` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `academic_calendar`
--

INSERT INTO `academic_calendar` (`id`, `event_name`, `event_date`, `description`, `event_type`, `display_order`, `status`, `created_at`) VALUES
(1, 'Start of Enrollment', '2025-06-01', 'Early registration begins for all grade levels', 'event', 1, 'active', '2026-05-03 11:02:22'),
(2, 'Enrollment Deadline', '2025-07-15', 'Last day for enrollment for SY 2025-2026', 'deadline', 2, 'active', '2026-05-03 11:02:22'),
(3, 'First Day of Classes', '2025-08-04', 'Opening of School Year 2025-2026', 'regular', 3, 'active', '2026-05-03 11:02:22'),
(4, 'Midterm Examinations', '2025-10-15', 'First quarter assessment period', 'exam', 4, 'active', '2026-05-03 11:02:22'),
(5, 'Final Examinations (1st Sem)', '2025-12-18', 'End of first semester assessments', 'exam', 5, 'active', '2026-05-03 11:02:22'),
(6, 'Resumption of Classes', '2026-01-05', 'Start of second semester', 'regular', 6, 'active', '2026-05-03 11:02:22'),
(7, 'Final Examinations (2nd Sem)', '2026-03-23', 'End of school year assessments', 'exam', 7, 'active', '2026-05-03 11:02:22'),
(8, 'Graduation Day', '2026-04-03', 'Commencement exercises for Grade 6, 10, and 12', 'event', 8, 'active', '2026-05-03 11:02:22'),
(9, 'Start of Enrollment', '2025-06-01', 'Early registration begins for all grade levels', 'event', 1, 'active', '2026-05-03 11:22:48'),
(10, 'Enrollment Deadline', '2025-07-15', 'Last day for enrollment for SY 2025-2026', 'deadline', 2, 'active', '2026-05-03 11:22:48'),
(11, 'First Day of Classes', '2025-08-04', 'Opening of School Year 2025-2026', 'regular', 3, 'active', '2026-05-03 11:22:48'),
(12, 'Midterm Examinations', '2025-10-15', 'First quarter assessment period', 'exam', 4, 'active', '2026-05-03 11:22:48'),
(13, 'Final Examinations (1st Sem)', '2025-12-18', 'End of first semester assessments', 'exam', 5, 'active', '2026-05-03 11:22:48'),
(14, 'Resumption of Classes', '2026-01-05', 'Start of second semester', 'regular', 6, 'active', '2026-05-03 11:22:48'),
(15, 'Final Examinations (2nd Sem)', '2026-03-23', 'End of school year assessments', 'exam', 7, 'active', '2026-05-03 11:22:48'),
(16, 'Graduation Day', '2026-04-03', 'Commencement exercises for Grade 6, 10, and 12', 'event', 8, 'active', '2026-05-03 11:22:48');

-- --------------------------------------------------------

--
-- Table structure for table `academic_levels`
--

CREATE TABLE `academic_levels` (
  `id` int(11) NOT NULL,
  `level_name` varchar(100) NOT NULL,
  `display_order` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `academic_levels`
--

INSERT INTO `academic_levels` (`id`, `level_name`, `display_order`, `status`, `created_at`) VALUES
(1, 'Kindergarten (Ages 3-6)', 1, 'active', '2026-05-03 11:02:18'),
(2, 'Elementary (Grades 1-6)', 2, 'active', '2026-05-03 11:02:18'),
(3, 'Junior High School (Grades 7-10)', 3, 'active', '2026-05-03 11:02:18'),
(4, 'Senior High School (Grades 11-12)', 4, 'active', '2026-05-03 11:02:18'),
(5, 'Kindergarten (Ages 3-6)', 1, 'active', '2026-05-03 11:22:47'),
(6, 'Elementary (Grades 1-6)', 2, 'active', '2026-05-03 11:22:47'),
(7, 'Junior High School (Grades 7-10)', 3, 'active', '2026-05-03 11:22:47'),
(8, 'Senior High School (Grades 11-12)', 4, 'active', '2026-05-03 11:22:47');

-- --------------------------------------------------------

--
-- Table structure for table `academic_programs`
--

CREATE TABLE `academic_programs` (
  `id` int(11) NOT NULL,
  `level_id` int(11) DEFAULT NULL,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `features` text DEFAULT NULL,
  `icon_class` varchar(100) DEFAULT 'fas fa-graduation-cap',
  `display_order` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `academic_programs`
--

INSERT INTO `academic_programs` (`id`, `level_id`, `title`, `description`, `features`, `icon_class`, `display_order`, `status`, `created_at`) VALUES
(1, 1, 'Early Childhood Education', 'Play-based learning that develops foundational skills in literacy, numeracy, and social interaction.', '[\"Montessori-inspired approach\",\"Filipino and English languages\",\"Music and movement classes\",\"Arts and crafts activities\"]', 'fas fa-child', 1, 'active', '2026-05-03 11:02:19'),
(2, 2, 'Enhanced Basic Education', 'Comprehensive curriculum focusing on core subjects with integrated values education.', '[\"Mathematics, Science, English, Filipino\",\"Computer and Technology classes\",\"Character Education program\",\"Weekly enrichment activities\"]', 'fas fa-book', 1, 'active', '2026-05-03 11:02:19'),
(3, 3, 'Junior High School Program', 'Advanced curriculum preparing students for Senior High School tracks with focus on critical thinking.', '[\"Specialized Science and Mathematics\",\"Research and ICT skills development\",\"Leadership training programs\",\"Career guidance seminars\"]', 'fas fa-flask', 1, 'active', '2026-05-03 11:02:19'),
(4, 4, 'STEM Strand', 'Science, Technology, Engineering, and Mathematics', '[\"Pre-Calculus & Basic Calculus\",\"General Biology, Chemistry, Physics\",\"Research Capstone Project\",\"Robotics and Programming\"]', 'fas fa-microscope', 1, 'active', '2026-05-03 11:02:19'),
(5, 4, 'ABM Strand', 'Accountancy, Business, and Management', '[\"Fundamentals of Accounting\",\"Business Mathematics & Finance\",\"Organizational Management\",\"Marketing Principles\"]', 'fas fa-chart-line', 2, 'active', '2026-05-03 11:02:19'),
(6, 4, 'HUMSS Strand', 'Humanities and Social Sciences', '[\"Philippine Politics & Governance\",\"Creative Writing & Journalism\",\"Introduction to World Religions\",\"Social Sciences Research\"]', 'fas fa-gavel', 3, 'active', '2026-05-03 11:02:19'),
(7, 4, 'TVL Track', 'Technical-Vocational-Livelihood', '[\"ICT - Computer Systems Servicing\",\"Home Economics - Bread & Pastry\",\"Industrial Arts - Electrical Installation\",\"Work Immersion Program (240 hours)\"]', 'fas fa-laptop-code', 4, 'active', '2026-05-03 11:02:19'),
(8, 1, 'Early Childhood Education', 'Play-based learning that develops foundational skills in literacy, numeracy, and social interaction.', '[\"Montessori-inspired approach\",\"Filipino and English languages\",\"Music and movement classes\",\"Arts and crafts activities\"]', 'fas fa-child', 1, 'active', '2026-05-03 11:22:47'),
(9, 2, 'Enhanced Basic Education', 'Comprehensive curriculum focusing on core subjects with integrated values education.', '[\"Mathematics, Science, English, Filipino\",\"Computer and Technology classes\",\"Character Education program\",\"Weekly enrichment activities\"]', 'fas fa-book', 1, 'active', '2026-05-03 11:22:47'),
(10, 3, 'Junior High School Program', 'Advanced curriculum preparing students for Senior High School tracks with focus on critical thinking.', '[\"Specialized Science and Mathematics\",\"Research and ICT skills development\",\"Leadership training programs\",\"Career guidance seminars\"]', 'fas fa-flask', 1, 'active', '2026-05-03 11:22:47'),
(11, 4, 'STEM Strand', 'Science, Technology, Engineering, and Mathematics', '[\"Pre-Calculus & Basic Calculus\",\"General Biology, Chemistry, Physics\",\"Research Capstone Project\",\"Robotics and Programming\"]', 'fas fa-microscope', 1, 'active', '2026-05-03 11:22:47'),
(12, 4, 'ABM Strand', 'Accountancy, Business, and Management', '[\"Fundamentals of Accounting\",\"Business Mathematics & Finance\",\"Organizational Management\",\"Marketing Principles\"]', 'fas fa-chart-line', 2, 'active', '2026-05-03 11:22:47'),
(13, 4, 'HUMSS Strand', 'Humanities and Social Sciences', '[\"Philippine Politics & Governance\",\"Creative Writing & Journalism\",\"Introduction to World Religions\",\"Social Sciences Research\"]', 'fas fa-gavel', 3, 'active', '2026-05-03 11:22:47'),
(14, 4, 'TVL Track', 'Technical-Vocational-Livelihood', '[\"ICT - Computer Systems Servicing\",\"Home Economics - Bread & Pastry\",\"Industrial Arts - Electrical Installation\",\"Work Immersion Program (240 hours)\"]', 'fas fa-laptop-code', 4, 'active', '2026-05-03 11:22:47');

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `role` enum('admin','editor','viewer') DEFAULT 'editor',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password`, `email`, `full_name`, `role`, `created_at`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@bethel.edu.ph', 'School Administrator', 'admin', '2026-05-03 11:02:16');

-- --------------------------------------------------------

--
-- Table structure for table `admissions_content`
--

CREATE TABLE `admissions_content` (
  `id` int(11) NOT NULL,
  `section` varchar(50) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admissions_content`
--

INSERT INTO `admissions_content` (`id`, `section`, `title`, `content`, `display_order`, `status`, `updated_at`) VALUES
(1, 'welcome', 'Welcome Future Eagles! 🦅', 'Bethel International School is now accepting applications for School Year 2025-2026. We invite you to become part of our growing community of learners who are committed to academic excellence, character development, and holistic growth.', 1, 'active', '2026-05-03 11:02:20'),
(2, 'enrollment_period', 'Enrollment Period', 'March 1 - July 15, 2025', 2, 'active', '2026-05-03 11:02:20'),
(3, 'classes_start', 'Classes Start', 'August 4, 2025', 3, 'active', '2026-05-03 11:02:20'),
(4, 'requirements', 'Requirements for Admission', 'Completed Application Form\nPSA Birth Certificate (Original & Photocopy)\nReport Card (SF9) from previous school\nGood Moral Certificate\n2 pcs. 2x2 ID picture (white background)\nMedical Certificate', 4, 'active', '2026-05-03 11:02:20');

-- --------------------------------------------------------

--
-- Table structure for table `admission_steps`
--

CREATE TABLE `admission_steps` (
  `id` int(11) NOT NULL,
  `step_number` int(2) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admission_steps`
--

INSERT INTO `admission_steps` (`id`, `step_number`, `title`, `description`, `display_order`, `status`, `created_at`) VALUES
(1, 1, 'Submit Application', 'Fill out the application form and submit required documents.', 1, 'active', '2026-05-03 11:02:21'),
(2, 2, 'Entrance Assessment', 'Schedule and take the entrance examination for your grade level.', 2, 'active', '2026-05-03 11:02:21'),
(3, 3, 'Interview', 'Parent and student interview with the school administration.', 3, 'active', '2026-05-03 11:02:21'),
(4, 4, 'Enrollment', 'Complete enrollment requirements and pay initial fees.', 4, 'active', '2026-05-03 11:02:21'),
(5, 1, 'Submit Application', 'Fill out the application form and submit required documents.', 1, 'active', '2026-05-03 11:22:47'),
(6, 2, 'Entrance Assessment', 'Schedule and take the entrance examination for your grade level.', 2, 'active', '2026-05-03 11:22:47'),
(7, 3, 'Interview', 'Parent and student interview with the school administration.', 3, 'active', '2026-05-03 11:22:47'),
(8, 4, 'Enrollment', 'Complete enrollment requirements and pay initial fees.', 4, 'active', '2026-05-03 11:22:47');

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `day` int(2) NOT NULL,
  `month` varchar(20) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `display_order` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `day`, `month`, `title`, `description`, `display_order`, `status`, `created_at`, `updated_at`) VALUES
(1, 15, 'June', 'Enrollment for SY 2025-2026', 'Enrollment for the School Year 2025-2026 is now open. Visit our campus in Pawing, Palo, Leyte for inquiries and campus tours.', 1, 'active', '2026-05-03 11:02:16', '2026-05-03 11:02:16'),
(2, 25, 'June', 'Philippine Eagle Festival', 'Join us for our annual Philippine Eagle Festival celebrating Filipino heritage and environmental conservation on June 25-29.', 2, 'active', '2026-05-03 11:02:16', '2026-05-03 11:02:16'),
(3, 12, 'June', 'Independence Day Celebration', 'Celebrate Philippine Independence Day with us on June 12 featuring cultural performances, historical exhibits, and patriotic activities.', 3, 'active', '2026-05-03 11:02:16', '2026-05-03 11:02:16'),
(4, 15, 'June', 'Enrollment for SY 2025-2026', 'Enrollment for the School Year 2025-2026 is now open. Visit our campus in Pawing, Palo, Leyte for inquiries and campus tours.', 1, 'active', '2026-05-03 11:22:46', '2026-05-03 11:22:46'),
(5, 25, 'June', 'Philippine Eagle Festival', 'Join us for our annual Philippine Eagle Festival celebrating Filipino heritage and environmental conservation on June 25-29.', 2, 'active', '2026-05-03 11:22:46', '2026-05-03 11:22:46'),
(6, 12, 'June', 'Independence Day Celebration', 'Celebrate Philippine Independence Day with us on June 12 featuring cultural performances, historical exhibits, and patriotic activities.', 3, 'active', '2026-05-03 11:22:46', '2026-05-03 11:22:46');

-- --------------------------------------------------------

--
-- Table structure for table `calendar_pdfs`
--

CREATE TABLE `calendar_pdfs` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `school_year` varchar(20) NOT NULL,
  `pdf_url` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `is_current` tinyint(1) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(200) DEFAULT NULL,
  `message` text NOT NULL,
  `status` enum('unread','read','replied') DEFAULT 'unread',
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `core_values`
--

CREATE TABLE `core_values` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `icon_class` varchar(50) DEFAULT 'fas fa-star',
  `display_order` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `core_values`
--

INSERT INTO `core_values` (`id`, `title`, `description`, `icon_class`, `display_order`, `status`, `created_at`, `updated_at`) VALUES
(0, 'Excellence', 'We strive for the highest standards in academics, character, and service.', 'fas fa-star', 0, 'active', '2026-05-03 11:42:32', '2026-05-03 11:42:32'),
(0, 'Faith', 'We nurture spiritual growth and moral integrity based on Christian values.', 'fas fa-star', 1, 'active', '2026-05-03 11:42:32', '2026-05-03 11:42:32'),
(0, 'Service', 'We develop compassionate leaders who serve their communities.', 'fas fa-star', 2, 'active', '2026-05-03 11:42:32', '2026-05-03 11:42:32'),
(0, 'Global Citizenship', 'We prepare students to thrive in an interconnected world while staying rooted in Filipino culture.', 'fas fa-star', 3, 'active', '2026-05-03 11:42:32', '2026-05-03 11:42:32'),
(0, 'Innovation', 'We embrace creativity and adapt to changing educational needs.', 'fas fa-star', 4, 'active', '2026-05-03 11:42:32', '2026-05-03 11:42:32');

-- --------------------------------------------------------

--
-- Table structure for table `features`
--

CREATE TABLE `features` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `icon_class` varchar(50) DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `features`
--

INSERT INTO `features` (`id`, `title`, `description`, `image_url`, `icon_class`, `display_order`, `status`, `created_at`, `updated_at`) VALUES
(1, 'World-Class Facilities', 'Our campus in Pawing, Palo features modern classrooms, science labs, sports facilities, and a well-stocked library to support holistic learning and innovation.', 'Images/Campus.png', NULL, 1, 'active', '2026-05-03 11:02:17', '2026-05-03 11:02:17'),
(2, 'International Curriculum', 'We offer an internationally-recognized curriculum combined with Filipino values and context to prepare students for global opportunities while remaining rooted in Philippine heritage.', 'Images/International.jpg', NULL, 2, 'active', '2026-05-03 11:02:17', '2026-05-03 11:02:17'),
(3, 'Soaring Talents Program', 'Inspired by the Philippine Eagle, our Soaring Talents Program offers sports, arts, music, leadership, and cultural activities to help students discover and develop their unique talents.', 'Images/Play.jpg', NULL, 3, 'active', '2026-05-03 11:02:17', '2026-05-03 11:02:17'),
(4, 'World-Class Facilities', 'Our campus in Pawing, Palo features modern classrooms, science labs, sports facilities, and a well-stocked library to support holistic learning and innovation.', 'Images/Campus.png', NULL, 1, 'active', '2026-05-03 11:22:46', '2026-05-03 11:22:46'),
(5, 'International Curriculum', 'We offer an internationally-recognized curriculum combined with Filipino values and context to prepare students for global opportunities while remaining rooted in Philippine heritage.', 'Images/International.jpg', NULL, 2, 'active', '2026-05-03 11:22:46', '2026-05-03 11:22:46'),
(6, 'Soaring Talents Program', 'Inspired by the Philippine Eagle, our Soaring Talents Program offers sports, arts, music, leadership, and cultural activities to help students discover and develop their unique talents.', 'Images/Play.jpg', NULL, 3, 'active', '2026-05-03 11:22:46', '2026-05-03 11:22:46');

-- --------------------------------------------------------

--
-- Table structure for table `hero_content`
--

CREATE TABLE `hero_content` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `subtitle` text DEFAULT NULL,
  `cta_text` varchar(100) DEFAULT NULL,
  `cta_link` varchar(255) DEFAULT NULL,
  `background_image` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hero_content`
--

INSERT INTO `hero_content` (`id`, `title`, `subtitle`, `cta_text`, `cta_link`, `background_image`, `updated_at`) VALUES
(1, '🦅 Soaring to Excellence in International Education', 'Inspired by the majesty of the Philippine Eagle, Bethel International School in Pawing, Palo, Leyte nurtures global citizens with strong Filipino values, academic excellence, and holistic development from kindergarten through senior high school.', 'Explore Our Programs', 'academics.php', NULL, '2026-05-03 11:02:17'),
(2, '🦅 Soaring to Excellence in International Education', 'Inspired by the majesty of the Philippine Eagle, Bethel International School in Pawing, Palo, Leyte nurtures global citizens with strong Filipino values, academic excellence, and holistic development from kindergarten through senior high school.', 'Explore Our Programs', 'academics.php', NULL, '2026-05-03 11:22:46');

-- --------------------------------------------------------

--
-- Table structure for table `newsletters`
--

CREATE TABLE `newsletters` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `issue_number` varchar(50) DEFAULT NULL,
  `month` varchar(20) DEFAULT NULL,
  `year` int(4) DEFAULT NULL,
  `pdf_url` varchar(255) DEFAULT NULL,
  `summary` text DEFAULT NULL,
  `featured_image` varchar(255) DEFAULT NULL,
  `status` enum('published','draft') DEFAULT 'draft',
  `published_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `newsletter_subscribers`
--

CREATE TABLE `newsletter_subscribers` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `status` enum('active','unsubscribed') DEFAULT 'active',
  `subscribed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `news_articles`
--

CREATE TABLE `news_articles` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `excerpt` text DEFAULT NULL,
  `content` text NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `category` enum('news','event','announcement','feature') DEFAULT 'news',
  `author` varchar(100) DEFAULT 'Bethel International School',
  `views` int(11) DEFAULT 0,
  `status` enum('draft','published') DEFAULT 'published',
  `is_highlight` tinyint(1) DEFAULT 0,
  `is_featured` tinyint(1) DEFAULT 0,
  `highlight_order` int(11) DEFAULT 0,
  `published_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `news_articles`
--

INSERT INTO `news_articles` (`id`, `title`, `excerpt`, `content`, `image_url`, `category`, `author`, `views`, `status`, `is_highlight`, `is_featured`, `highlight_order`, `published_date`, `created_at`, `updated_at`) VALUES
(1, 'Bethel International School Ranks Top 10 in Regional Science Fair', 'Our students brought home 5 awards from the Regional Schools Press Conference, showcasing excellence in journalism and public speaking.', 'Our students showcased exceptional talent at the Regional Schools Press Conference. The team won first place in science investigatory project, second place in robotics competition, and received special awards for innovation and creativity. This achievement reflects our commitment to STEM education and research excellence.', 'https://images.unsplash.com/photo-1562774053-701939374585?w=600', 'news', 'Bethel International School', 0, 'published', 1, 1, 1, '2026-05-03', '2026-05-03 11:02:18', '2026-05-03 11:02:18'),
(2, 'Annual Sports Festival 2026', 'Join us for a week of friendly competition, sportsmanship, and athletic excellence.', 'The Annual Sports Festival will take place from April 5-10, 2026. Events include basketball, volleyball, badminton, swimming, and track and field. All students are encouraged to participate.', 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=600', 'event', 'Bethel International School', 0, 'published', 1, 0, 2, '2026-05-13', '2026-05-03 11:02:18', '2026-05-03 11:02:18'),
(3, 'Early Registration for SY 2026-2027 Now Open', 'Secure your child\'s slot with a 10% discount on tuition fees.', 'Early registration for School Year 2026-2027 is now open until May 30, 2026. Enroll now to enjoy a 10% discount on tuition fees and free school supplies.', 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=600', 'announcement', 'Bethel International School', 0, 'published', 1, 0, 3, '2026-05-03', '2026-05-03 11:02:18', '2026-05-03 11:02:18'),
(4, 'Bethel International School Ranks Top 10 in Regional Science Fair', 'Our students brought home 5 awards from the Regional Schools Press Conference, showcasing excellence in journalism and public speaking.', 'Our students showcased exceptional talent at the Regional Schools Press Conference. The team won first place in science investigatory project, second place in robotics competition, and received special awards for innovation and creativity. This achievement reflects our commitment to STEM education and research excellence.', 'https://images.unsplash.com/photo-1562774053-701939374585?w=600', 'news', 'Bethel International School', 0, 'published', 1, 1, 1, '2026-05-03', '2026-05-03 11:22:46', '2026-05-03 11:22:46'),
(5, 'Annual Sports Festival 2026', 'Join us for a week of friendly competition, sportsmanship, and athletic excellence.', 'The Annual Sports Festival will take place from April 5-10, 2026. Events include basketball, volleyball, badminton, swimming, and track and field. All students are encouraged to participate.', 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=600', 'event', 'Bethel International School', 0, 'published', 1, 0, 2, '2026-05-13', '2026-05-03 11:22:46', '2026-05-03 11:22:46'),
(6, 'Early Registration for SY 2026-2027 Now Open', 'Secure your child\'s slot with a 10% discount on tuition fees.', 'Early registration for School Year 2026-2027 is now open until May 30, 2026. Enroll now to enjoy a 10% discount on tuition fees and free school supplies.', 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=600', 'announcement', 'Bethel International School', 0, 'published', 1, 0, 3, '2026-05-03', '2026-05-03 11:22:46', '2026-05-03 11:22:46');

-- --------------------------------------------------------

--
-- Table structure for table `school_settings`
--

CREATE TABLE `school_settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `setting_type` enum('text','textarea','image','number') DEFAULT 'text',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `school_settings`
--

INSERT INTO `school_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `updated_at`) VALUES
(1, 'school_name', 'Bethel International School', 'text', '2026-05-03 11:02:25'),
(2, 'school_address', 'Pawing, Palo, Leyte, Philippines 6501', 'text', '2026-05-03 11:02:25'),
(3, 'school_phone', '0917-173-0284', 'text', '2026-05-03 11:02:25'),
(4, 'school_email', 'secretary@bethel.edu.ph', 'text', '2026-05-03 11:02:25'),
(5, 'facebook_url', 'https://www.facebook.com/BethelInternationalSchool', 'text', '2026-05-03 11:02:25'),
(6, 'instagram_url', 'https://www.instagram.com/bethel.sc/', 'text', '2026-05-03 11:02:25'),
(7, 'emergency_hotline', '0917-173-0284', 'text', '2026-05-03 11:02:25'),
(8, 'newsletter_coming_soon', '0', 'text', '2026-05-03 11:02:25'),
(9, 'calendar_coming_soon', '0', 'text', '2026-05-03 11:02:25');

-- --------------------------------------------------------

--
-- Table structure for table `special_programs`
--

CREATE TABLE `special_programs` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `icon_class` varchar(50) DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `special_programs`
--

INSERT INTO `special_programs` (`id`, `title`, `description`, `icon_class`, `display_order`, `status`, `created_at`) VALUES
(1, 'International Exchange', 'Student exchange programs with partner schools in Japan, South Korea, and the United States.', 'fas fa-globe', 1, 'active', '2026-05-03 11:02:19'),
(2, 'Robotics Club', 'After-school program teaching robotics, programming, and emerging technologies.', 'fas fa-robot', 2, 'active', '2026-05-03 11:02:19'),
(3, 'Center for the Arts', 'Specialized training in music, dance, theater, and visual arts.', 'fas fa-music', 3, 'active', '2026-05-03 11:02:19'),
(4, 'Values Formation', 'Weekly sessions focusing on character development, leadership, and community service.', 'fas fa-hand-holding-heart', 4, 'active', '2026-05-03 11:02:19'),
(5, 'International Exchange', 'Student exchange programs with partner schools in Japan, South Korea, and the United States.', 'fas fa-globe', 1, 'active', '2026-05-03 11:22:47'),
(6, 'Robotics Club', 'After-school program teaching robotics, programming, and emerging technologies.', 'fas fa-robot', 2, 'active', '2026-05-03 11:22:47'),
(7, 'Center for the Arts', 'Specialized training in music, dance, theater, and visual arts.', 'fas fa-music', 3, 'active', '2026-05-03 11:22:47'),
(8, 'Values Formation', 'Weekly sessions focusing on character development, leadership, and community service.', 'fas fa-hand-holding-heart', 4, 'active', '2026-05-03 11:22:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `about_content`
--
ALTER TABLE `about_content`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `section` (`section`);

--
-- Indexes for table `academic_calendar`
--
ALTER TABLE `academic_calendar`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `academic_levels`
--
ALTER TABLE `academic_levels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `academic_programs`
--
ALTER TABLE `academic_programs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `admissions_content`
--
ALTER TABLE `admissions_content`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `section` (`section`);

--
-- Indexes for table `admission_steps`
--
ALTER TABLE `admission_steps`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_announcements_status` (`status`,`display_order`);

--
-- Indexes for table `calendar_pdfs`
--
ALTER TABLE `calendar_pdfs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_contact_status` (`status`,`created_at`);

--
-- Indexes for table `features`
--
ALTER TABLE `features`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hero_content`
--
ALTER TABLE `hero_content`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `newsletters`
--
ALTER TABLE `newsletters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `newsletter_subscribers`
--
ALTER TABLE `newsletter_subscribers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `news_articles`
--
ALTER TABLE `news_articles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_news_status` (`status`,`published_date`),
  ADD KEY `idx_news_highlight` (`is_highlight`,`highlight_order`);

--
-- Indexes for table `school_settings`
--
ALTER TABLE `school_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indexes for table `special_programs`
--
ALTER TABLE `special_programs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `about_content`
--
ALTER TABLE `about_content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `academic_calendar`
--
ALTER TABLE `academic_calendar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `academic_levels`
--
ALTER TABLE `academic_levels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `academic_programs`
--
ALTER TABLE `academic_programs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `admissions_content`
--
ALTER TABLE `admissions_content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `admission_steps`
--
ALTER TABLE `admission_steps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `calendar_pdfs`
--
ALTER TABLE `calendar_pdfs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `features`
--
ALTER TABLE `features`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `hero_content`
--
ALTER TABLE `hero_content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `newsletters`
--
ALTER TABLE `newsletters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `newsletter_subscribers`
--
ALTER TABLE `newsletter_subscribers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `news_articles`
--
ALTER TABLE `news_articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `school_settings`
--
ALTER TABLE `school_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `special_programs`
--
ALTER TABLE `special_programs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
