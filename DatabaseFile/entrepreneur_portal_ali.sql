-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 29, 2026 at 09:36 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `entrepreneur_portal_ali`
--

-- --------------------------------------------------------

--
-- Table structure for table `agent_profiles`
--

CREATE TABLE `agent_profiles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `business_domain` varchar(150) NOT NULL,
  `bio` text DEFAULT NULL,
  `experience_yrs` tinyint(3) UNSIGNED DEFAULT 0,
  `linkedin_url` varchar(255) DEFAULT NULL,
  `website_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `agent_profiles`
--

INSERT INTO `agent_profiles` (`id`, `user_id`, `business_domain`, `bio`, `experience_yrs`, `linkedin_url`, `website_url`, `created_at`, `updated_at`) VALUES
(1, 3, 'Farming', 'DYZ', 22, 'https://www.youtube.com/watch?v=jWJEO-76KMw', 'https://www.youtube.com/watch?v=jWJEO-76KMw', '2026-03-28 07:55:11', '2026-03-28 07:55:11');

-- --------------------------------------------------------

--
-- Table structure for table `business_categories`
--

CREATE TABLE `business_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `business_categories`
--

INSERT INTO `business_categories` (`id`, `name`, `description`, `created_at`) VALUES
(2, 'Technology', 'Businesses related to software, hardware, and IT services', '2026-03-28 08:02:10'),
(3, 'Healthcare', 'Medical services, health products, and wellness businesses', '2026-03-28 08:02:10'),
(4, 'Education', 'Schools, training centers, online courses, and tutoring services', '2026-03-28 08:02:10'),
(5, 'Food & Beverage', 'Restaurants, cafes, catering, and food delivery services', '2026-03-28 08:02:10'),
(6, 'Fashion & Apparel', 'Clothing, accessories, footwear, and fashion design', '2026-03-28 08:02:10'),
(7, 'E-commerce', 'Online stores, marketplaces, and retail platforms', '2026-03-28 08:02:10'),
(8, 'Real Estate', 'Property sales, rentals, and real estate agencies', '2026-03-28 08:02:10'),
(9, 'Finance & Banking', 'Banks, fintech startups, accounting, and investment services', '2026-03-28 08:02:10'),
(10, 'Travel & Tourism', 'Travel agencies, tour operators, and hospitality businesses', '2026-03-28 08:02:10'),
(11, 'Automotive', 'Car dealerships, repair shops, and vehicle services', '2026-03-28 08:02:10'),
(12, 'Beauty & Personal Care', 'Salons, spas, cosmetic products, and personal care services', '2026-03-28 08:02:10'),
(13, 'Sports & Fitness', 'Gyms, fitness centers, sports coaching, and equipment sales', '2026-03-28 08:02:10'),
(14, 'Entertainment & Media', 'TV, radio, film, music, and content production businesses', '2026-03-28 08:02:10'),
(15, 'Home & Lifestyle', 'Furniture, home decor, and lifestyle products', '2026-03-28 08:02:10'),
(16, 'Logistics & Transportation', 'Freight, courier services, and transport businesses', '2026-03-28 08:02:10'),
(17, 'Agriculture & Farming', 'Farms, agro products, and agricultural services', '2026-03-28 08:02:10'),
(18, 'Marketing & Advertising', 'Marketing agencies, branding, and digital advertising services', '2026-03-28 08:02:10'),
(19, 'Legal Services', 'Law firms, legal consultancy, and advisory services', '2026-03-28 08:02:10'),
(20, 'Energy & Environment', 'Renewable energy, solar, and environmental solutions', '2026-03-28 08:02:10'),
(21, 'Consulting & Professional Services', 'Business consulting, HR, and advisory services', '2026-03-28 08:02:10');

-- --------------------------------------------------------

--
-- Table structure for table `business_ideas`
--

CREATE TABLE `business_ideas` (
  `id` int(11) NOT NULL,
  `agent_id` int(11) NOT NULL COMMENT 'FK to users',
  `category_id` int(11) NOT NULL COMMENT 'FK to business_categories',
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `business_ideas`
--

INSERT INTO `business_ideas` (`id`, `agent_id`, `category_id`, `title`, `description`, `created_at`, `updated_at`) VALUES
(2, 3, 2, 'AI-Powered Health Tracker', 'An app that monitors vital signs and provides personalized health insights.', '2026-03-28 08:06:37', '2026-03-28 08:06:37'),
(3, 3, 3, 'Online Tutoring Platform', 'A platform connecting students with expert tutors worldwide.', '2026-03-28 08:06:37', '2026-03-28 08:06:37'),
(4, 3, 4, 'Organic Meal Delivery', 'Deliver fresh, organic meals directly to customers’ doors.', '2026-03-28 08:06:37', '2026-03-28 08:06:37'),
(5, 3, 5, 'Eco-Friendly Clothing Line', 'Sustainable fashion brand using recycled materials.', '2026-03-28 08:06:37', '2026-03-28 08:06:37'),
(6, 3, 6, 'Local Handmade Marketplace', 'E-commerce platform for local artisans and crafters.', '2026-03-28 08:06:37', '2026-03-28 08:06:37'),
(7, 3, 7, 'Smart Real Estate Portal', 'An app that helps users find and manage real estate investments.', '2026-03-28 08:06:37', '2026-03-28 08:06:37'),
(8, 3, 8, 'Fintech Budget App', 'An app that helps users track expenses and savings goals.', '2026-03-28 08:06:37', '2026-03-28 08:06:37'),
(9, 3, 9, 'Adventure Travel Planner', 'A platform for booking unique adventure trips and experiences.', '2026-03-28 08:06:37', '2026-03-28 08:06:37'),
(10, 3, 10, 'Electric Vehicle Repair', 'Specialized services for electric cars and hybrid vehicles.', '2026-03-28 08:06:37', '2026-03-28 08:06:37'),
(11, 3, 11, 'Luxury Spa Services', 'Premium spa and wellness services with home delivery options.', '2026-03-28 08:06:37', '2026-03-28 08:06:37'),
(12, 3, 12, 'Virtual Fitness Coaching', 'Personalized fitness coaching via video and AI analysis.', '2026-03-28 08:06:37', '2026-03-28 08:06:37'),
(13, 3, 13, 'Content Production Agency', 'Professional services for video, music, and digital content creation.', '2026-03-28 08:06:37', '2026-03-28 08:06:37'),
(14, 3, 14, 'Smart Home Solutions', 'IoT products for home automation and energy efficiency.', '2026-03-28 08:06:37', '2026-03-28 08:06:37'),
(15, 3, 15, 'Courier & Logistics App', 'Platform to track deliveries and manage logistics for SMEs.', '2026-03-28 08:06:37', '2026-03-28 08:06:37'),
(16, 3, 16, 'Organic Farm Produce', 'Direct-to-consumer organic fruits, vegetables, and dairy.', '2026-03-28 08:06:37', '2026-03-28 08:06:37'),
(17, 3, 17, 'Digital Marketing Agency', 'SEO, social media, and content marketing solutions.', '2026-03-28 08:06:37', '2026-03-28 08:06:37'),
(18, 3, 18, 'Legal Consultation Online', 'Virtual legal services and document review.', '2026-03-28 08:06:37', '2026-03-28 08:06:37'),
(19, 3, 19, 'Solar Energy Installation', 'Affordable solar panel installation and maintenance services.', '2026-03-28 08:06:37', '2026-03-28 08:06:37'),
(20, 3, 20, 'Business Consulting Services', 'Professional business, HR, and management consulting.', '2026-03-28 08:06:37', '2026-03-28 08:06:37'),
(21, 3, 21, 'Virtual Reality Education', 'Immersive VR experiences for students and professionals.', '2026-03-28 08:06:37', '2026-03-28 08:06:37');

-- --------------------------------------------------------

--
-- Table structure for table `business_resources`
--

CREATE TABLE `business_resources` (
  `resource_id` int(11) NOT NULL,
  `agent_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `content` text DEFAULT NULL,
  `type` enum('framework','market_research','startup_plan','funding_guide','legal_document','case_study') NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `business_resources`
--

INSERT INTO `business_resources` (`resource_id`, `agent_id`, `category_id`, `title`, `content`, `type`, `description`, `created_at`) VALUES
(2, 3, 2, 'E-commerce Growth Framework', 'Step-by-step guide for scaling online stores.', 'framework', 'Proven framework for small to medium e-commerce businesses.', '2026-03-28 08:29:46'),
(3, 3, 3, 'Market Trends Report 2026', 'Analysis of consumer electronics market trends.', 'market_research', 'Comprehensive report on electronics market for investors and startups.', '2026-03-28 08:29:46'),
(4, 3, 4, 'Startup Launch Plan', 'Detailed plan to launch your startup in 90 days.', 'startup_plan', 'Practical startup launch plan covering funding, team, and marketing.', '2026-03-28 08:29:46'),
(5, 3, 5, 'Seed Funding Guide', 'Guide to approach angel investors and VCs.', 'funding_guide', 'Step-by-step instructions for raising seed funding.', '2026-03-28 08:29:46'),
(6, 3, 6, 'Legal Documents Pack', 'Contracts, NDAs, and agreements templates.', 'legal_document', 'Essential legal documents for small businesses.', '2026-03-28 08:29:46'),
(7, 3, 7, 'Case Study: Food Delivery App', 'How a small app scaled to 100k users.', 'case_study', 'Real-world example of successful app growth strategies.', '2026-03-28 08:29:46'),
(8, 3, 8, 'SaaS Product Framework', 'Framework for SaaS product development and launch.', 'framework', 'Structured approach for SaaS startups.', '2026-03-28 08:29:46'),
(9, 3, 9, 'Retail Market Research', 'Insights into local retail business opportunities.', 'market_research', 'Useful for agents looking to invest in retail markets.', '2026-03-28 08:29:46'),
(10, 3, 10, 'Business Plan Template', 'Editable startup plan template for tech ventures.', 'startup_plan', 'Fillable template to outline your business idea.', '2026-03-28 08:29:46'),
(11, 3, 11, 'Funding Strategy Guide', 'Stepwise approach to secure Series A funding.', 'funding_guide', 'Practical advice on pitching and investor communication.', '2026-03-28 08:29:46'),
(12, 3, 12, 'Employment Contract Template', 'Template for hiring full-time employees.', 'legal_document', 'Standardized contract for small businesses and startups.', '2026-03-28 08:29:46'),
(13, 3, 13, 'Case Study: Online Education Platform', 'How EduTech startup grew user base to 50k in 6 months.', 'case_study', 'Insights into marketing and content strategy.', '2026-03-28 08:29:46'),
(14, 3, 14, 'Digital Marketing Framework', 'Framework for social media and content marketing.', 'framework', 'Step-by-step marketing framework for online businesses.', '2026-03-28 08:29:46'),
(15, 3, 15, 'Consumer Behavior Report', 'Trends in consumer behavior for FMCG products.', 'market_research', 'Detailed analysis for strategic decision-making.', '2026-03-28 08:29:46'),
(16, 3, 16, 'Business Model Template', 'Template to outline your revenue and cost structures.', 'startup_plan', 'Easy-to-use template for business planning.', '2026-03-28 08:29:46'),
(17, 3, 17, 'Investor Pitch Guide', 'Best practices for preparing a compelling pitch deck.', 'funding_guide', 'Guide to attract investors effectively.', '2026-03-28 08:29:46'),
(18, 3, 18, 'Terms & Conditions Template', 'Legal template for website/app T&Cs.', 'legal_document', 'Protect your business with proper T&Cs.', '2026-03-28 08:29:46'),
(19, 3, 19, 'Case Study: Fitness App', 'How a fitness app achieved 10k active users in 3 months.', 'case_study', 'Practical growth strategies and lessons learned.', '2026-03-28 08:29:46'),
(20, 3, 20, 'Product Launch Framework', 'Stepwise framework to plan and execute product launches.', 'framework', 'For startups looking to launch products successfully.', '2026-03-28 08:29:46'),
(21, 3, 21, 'Market Research: Mobile Apps', 'Analysis of top mobile app categories in 2026.', 'market_research', 'Insights to guide app development and marketing strategies.', '2026-03-28 08:29:46');

-- --------------------------------------------------------

--
-- Table structure for table `business_templates`
--

CREATE TABLE `business_templates` (
  `id` int(11) NOT NULL,
  `agent_id` int(11) NOT NULL COMMENT 'FK to users',
  `category_id` int(11) DEFAULT NULL COMMENT 'FK to business_categories',
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `file_url` varchar(500) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `business_templates`
--

INSERT INTO `business_templates` (`id`, `agent_id`, `category_id`, `title`, `description`, `file_url`, `created_at`, `updated_at`) VALUES
(2, 3, 2, 'Daily Health Report Template', 'A comprehensive daily health tracking template for clinics and wellness centers.', 'templates/1774685443_Health_Report_daily.pdf', '2026-03-28 08:12:42', '2026-03-28 08:12:42'),
(3, 3, 3, 'Startup Business Plan Template', 'Step-by-step business plan template to help startups outline strategy and goals.', 'templates/1774685443_Health_Report_daily.pdf', '2026-03-28 08:12:42', '2026-03-28 08:12:42'),
(4, 3, 4, 'Marketing Campaign Tracker', 'Template to manage and track marketing campaigns across multiple channels.', 'templates/1774685443_Health_Report_daily.pdf', '2026-03-28 08:12:42', '2026-03-28 08:12:42'),
(5, 3, 5, 'Sales Invoice Template', 'Professional invoice template for tracking sales and payments for small businesses.', 'templates/1774685443_Health_Report_daily.pdf', '2026-03-28 08:12:42', '2026-03-28 08:12:42'),
(6, 3, 6, 'Customer Feedback Form', 'Template to collect structured feedback from customers or clients.', 'templates/1774685443_Health_Report_daily.pdf', '2026-03-28 08:12:42', '2026-03-28 08:12:42'),
(7, 3, 7, 'Project Management Tracker', 'Gantt-style template to plan, execute, and monitor business projects.', 'templates/1774685443_Health_Report_daily.pdf', '2026-03-28 08:12:42', '2026-03-28 08:12:42'),
(8, 3, 8, 'Financial Statement Template', 'Template to create monthly or quarterly financial statements for SMEs.', 'templates/1774685443_Health_Report_daily.pdf', '2026-03-28 08:12:42', '2026-03-28 08:12:42'),
(9, 3, 9, 'Employee Performance Review', 'Standardized template for evaluating employee performance and goal tracking.', 'templates/1774685443_Health_Report_daily.pdf', '2026-03-28 08:12:42', '2026-03-28 08:12:42'),
(10, 3, 10, 'Social Media Content Calendar', 'Template to plan and schedule social media posts for multiple platforms.', 'templates/1774685443_Health_Report_daily.pdf', '2026-03-28 08:12:42', '2026-03-28 08:12:42'),
(11, 3, 11, 'Business SWOT Analysis', 'Template to analyze Strengths, Weaknesses, Opportunities, and Threats of a business.', 'templates/1774685443_Health_Report_daily.pdf', '2026-03-28 08:12:42', '2026-03-28 08:12:42'),
(12, 3, 12, 'Client Onboarding Checklist', 'Step-by-step template to onboard new clients efficiently.', 'templates/1774685443_Health_Report_daily.pdf', '2026-03-28 08:12:42', '2026-03-28 08:12:42'),
(13, 3, 13, 'Product Launch Plan', 'Template for planning product launches including timeline, marketing, and budget.', 'templates/1774685443_Health_Report_daily.pdf', '2026-03-28 08:12:42', '2026-03-28 08:12:42'),
(14, 3, 14, 'Expense Tracking Sheet', 'Detailed template for tracking daily, weekly, and monthly expenses.', 'templates/1774685443_Health_Report_daily.pdf', '2026-03-28 08:12:42', '2026-03-28 08:12:42'),
(15, 3, 15, 'Investor Pitch Deck Template', 'Professional pitch deck structure for presenting startups to investors.', 'templates/1774685443_Health_Report_daily.pdf', '2026-03-28 08:12:42', '2026-03-28 08:12:42'),
(16, 3, 16, 'Meeting Agenda Template', 'Structured agenda template to organize and run productive business meetings.', 'templates/1774685443_Health_Report_daily.pdf', '2026-03-28 08:12:42', '2026-03-28 08:12:42'),
(17, 3, 17, 'Customer Journey Map', 'Template to map customer touchpoints and improve business experience.', 'templates/1774685443_Health_Report_daily.pdf', '2026-03-28 08:12:42', '2026-03-28 08:12:42'),
(18, 3, 18, 'Weekly Team Task Planner', 'Template to assign and track weekly tasks for team members.', 'templates/1774685443_Health_Report_daily.pdf', '2026-03-28 08:12:42', '2026-03-28 08:12:42'),
(19, 3, 19, 'Marketing Budget Planner', 'Template to plan and monitor marketing expenses effectively.', 'templates/1774685443_Health_Report_daily.pdf', '2026-03-28 08:12:42', '2026-03-28 08:12:42'),
(20, 3, 20, 'Event Planning Checklist', 'Comprehensive checklist template for planning corporate or social events.', 'templates/1774685443_Health_Report_daily.pdf', '2026-03-28 08:12:42', '2026-03-28 08:12:42'),
(21, 3, 21, 'Business Proposal Template', 'Professional template for creating business proposals for clients or investors.', 'templates/1774685443_Health_Report_daily.pdf', '2026-03-28 08:12:42', '2026-03-28 08:12:42');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `event_date` datetime NOT NULL,
  `end_date` datetime DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `mode` enum('online','offline','hybrid') NOT NULL DEFAULT 'online',
  `event_link` varchar(500) DEFAULT NULL,
  `thumbnail` varchar(500) DEFAULT NULL,
  `status` enum('upcoming','ongoing','completed','cancelled') NOT NULL DEFAULT 'upcoming',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `description`, `event_date`, `end_date`, `location`, `mode`, `event_link`, `thumbnail`, `status`, `created_at`, `updated_at`) VALUES
(3, 'Startup Pitch Day', 'Present your startup ideas to investors.', '2026-04-05 10:00:00', '2026-04-05 15:00:00', 'Lahore', 'offline', 'https://example.com/event1', '1774766930_App.jpg', 'upcoming', '2026-03-29 06:50:31', '2026-03-29 06:50:31'),
(4, 'Digital Marketing Bootcamp', 'Learn SEO, Ads, and social media growth.', '2026-04-07 09:00:00', '2026-04-07 13:00:00', 'Online', 'online', 'https://example.com/event2', '1774766930_App.jpg', 'upcoming', '2026-03-29 06:50:31', '2026-03-29 06:50:31'),
(5, 'Tech Networking Meetup', 'Connect with developers and entrepreneurs.', '2026-04-10 18:00:00', '2026-04-10 21:00:00', 'Karachi', 'offline', 'https://example.com/event3', '1774766930_App.jpg', 'upcoming', '2026-03-29 06:50:31', '2026-03-29 06:50:31'),
(6, 'AI & Machine Learning Workshop', 'Hands-on training with AI tools.', '2026-04-12 11:00:00', '2026-04-12 16:00:00', 'Islamabad', 'hybrid', 'https://example.com/event4', '1774766930_App.jpg', 'upcoming', '2026-03-29 06:50:31', '2026-03-29 06:50:31'),
(7, 'Freelancing Masterclass', 'Learn how to earn online effectively.', '2026-04-15 14:00:00', '2026-04-15 17:00:00', 'Online', 'online', 'https://example.com/event5', '1774766930_App.jpg', 'upcoming', '2026-03-29 06:50:31', '2026-03-29 06:50:31'),
(8, 'E-commerce Growth Summit', 'Boost your online store sales.', '2026-04-18 10:00:00', '2026-04-18 16:00:00', 'Faisalabad', 'offline', 'https://example.com/event6', '1774766930_App.jpg', 'upcoming', '2026-03-29 06:50:31', '2026-03-29 06:50:31'),
(9, 'Mobile App Development Bootcamp', 'Build Android & iOS apps.', '2026-04-20 09:00:00', '2026-04-20 15:00:00', 'Multan', 'offline', 'https://example.com/event7', '1774766930_App.jpg', 'upcoming', '2026-03-29 06:50:31', '2026-03-29 06:50:31'),
(10, 'Startup Funding Webinar', 'Learn how to raise investment.', '2026-04-22 19:00:00', '2026-04-22 21:00:00', 'Online', 'online', 'https://example.com/event8', '1774766930_App.jpg', 'upcoming', '2026-03-29 06:50:31', '2026-03-29 06:50:31'),
(11, 'UI/UX Design Workshop', 'Improve your design skills.', '2026-04-25 11:00:00', '2026-04-25 14:00:00', 'Lahore', 'offline', 'https://example.com/event9', '1774766930_App.jpg', 'upcoming', '2026-03-29 06:50:31', '2026-03-29 06:50:31'),
(12, 'Blockchain Basics Seminar', 'Introduction to blockchain tech.', '2026-04-27 12:00:00', '2026-04-27 16:00:00', 'Islamabad', 'hybrid', 'https://example.com/event10', '1774766930_App.jpg', 'upcoming', '2026-03-29 06:50:31', '2026-03-29 06:50:31'),
(13, 'Content Creation Bootcamp', 'Create engaging social media content.', '2026-04-29 10:00:00', '2026-04-29 13:00:00', 'Online', 'online', 'https://example.com/event11', '1774766930_App.jpg', 'upcoming', '2026-03-29 06:50:31', '2026-03-29 06:50:31'),
(14, 'Entrepreneurship Summit', 'Learn business strategies from experts.', '2026-05-02 09:00:00', '2026-05-02 17:00:00', 'Karachi', 'offline', 'https://example.com/event12', '1774766930_App.jpg', 'upcoming', '2026-03-29 06:50:31', '2026-03-29 06:50:31'),
(15, 'Cybersecurity Awareness Workshop', 'Protect your systems from threats.', '2026-05-05 11:00:00', '2026-05-05 15:00:00', 'Online', 'online', 'https://example.com/event13', '1774766930_App.jpg', 'upcoming', '2026-03-29 06:50:31', '2026-03-29 06:50:31'),
(16, 'Data Science Bootcamp', 'Learn Python, Pandas, ML basics.', '2026-05-07 10:00:00', '2026-05-07 16:00:00', 'Lahore', 'offline', 'https://example.com/event14', '1774766930_App.jpg', 'upcoming', '2026-03-29 06:50:31', '2026-03-29 06:50:31'),
(17, 'Public Speaking Training', 'Boost your communication skills.', '2026-05-10 14:00:00', '2026-05-10 17:00:00', 'Multan', 'offline', 'https://example.com/event15', '1774766930_App.jpg', 'upcoming', '2026-03-29 06:50:31', '2026-03-29 06:50:31'),
(18, 'Business Growth Strategies', 'Scale your startup effectively.', '2026-05-12 10:00:00', '2026-05-12 15:00:00', 'Islamabad', 'hybrid', 'https://example.com/event16', '1774766930_App.jpg', 'upcoming', '2026-03-29 06:50:31', '2026-03-29 06:50:31'),
(19, 'Affiliate Marketing Workshop', 'Earn passive income online.', '2026-05-15 12:00:00', '2026-05-15 15:00:00', 'Online', 'online', 'https://example.com/event17', '1774766930_App.jpg', 'upcoming', '2026-03-29 06:50:31', '2026-03-29 06:50:31'),
(20, 'Cloud Computing Seminar', 'AWS, Azure basics for developers.', '2026-05-18 11:00:00', '2026-05-18 16:00:00', 'Karachi', 'offline', 'https://example.com/event18', '1774766930_App.jpg', 'upcoming', '2026-03-29 06:50:31', '2026-03-29 06:50:31'),
(21, 'Networking Skills Workshop', 'Build strong professional networks.', '2026-05-20 13:00:00', '2026-05-20 16:00:00', 'Faisalabad', 'offline', 'https://example.com/event19', '1774766930_App.jpg', 'upcoming', '2026-03-29 06:50:31', '2026-03-29 06:50:31'),
(22, 'Startup Legal Basics', 'Understand contracts & compliance.', '2026-05-22 10:00:00', '2026-05-22 13:00:00', 'Online', 'online', 'https://example.com/event20', '1774766930_App.jpg', 'upcoming', '2026-03-29 06:50:31', '2026-03-29 06:50:31');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `agent_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'Who submitted the feedback',
  `message` text NOT NULL,
  `rating` tinyint(1) UNSIGNED DEFAULT NULL COMMENT '1 to 5 stars',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `agent_id`, `user_id`, `message`, `rating`, `created_at`) VALUES
(1, 3, 1, 'Good', 3, '2026-03-28 09:08:40'),
(2, 3, 2, 'dddd', 3, '2026-03-29 06:56:41');

-- --------------------------------------------------------

--
-- Table structure for table `forum_replies`
--

CREATE TABLE `forum_replies` (
  `id` int(11) NOT NULL,
  `thread_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reply` longtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `forum_replies`
--

INSERT INTO `forum_replies` (`id`, `thread_id`, `user_id`, `reply`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 'Hy', '2026-03-28 08:38:18', '2026-03-28 08:38:18'),
(2, 1, 1, 'YEs', '2026-03-28 08:42:50', '2026-03-28 08:42:50');

-- --------------------------------------------------------

--
-- Table structure for table `forum_threads`
--

CREATE TABLE `forum_threads` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `body` longtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `forum_threads`
--

INSERT INTO `forum_threads` (`id`, `user_id`, `category_id`, `title`, `body`, `created_at`, `updated_at`) VALUES
(1, 3, 17, 'fd', 'fds', '2026-03-28 08:37:53', '2026-03-28 08:37:53');

-- --------------------------------------------------------

--
-- Table structure for table `opportunities`
--

CREATE TABLE `opportunities` (
  `id` int(11) NOT NULL,
  `agent_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `type` enum('investment','partnership','franchise','co-founder','other') NOT NULL DEFAULT 'other',
  `location` varchar(150) DEFAULT NULL,
  `deadline` date DEFAULT NULL,
  `status` enum('open','closed','draft') NOT NULL DEFAULT 'open',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `opportunities`
--

INSERT INTO `opportunities` (`id`, `agent_id`, `category_id`, `title`, `description`, `type`, `location`, `deadline`, `status`, `created_at`, `updated_at`) VALUES
(1, 3, 2, 'Wellness Center Investment', 'Seeking investors for a new wellness and fitness center in downtown.', 'investment', 'New York, USA', '2026-06-30', 'open', '2026-03-28 08:16:09', '2026-03-28 08:16:09'),
(2, 3, 3, 'Eco-friendly Packaging Partnership', 'Looking for partners to expand sustainable packaging business.', 'partnership', 'San Francisco, USA', '2026-07-15', 'open', '2026-03-28 08:16:09', '2026-03-28 08:16:09'),
(3, 3, 4, 'Local Cafe Franchise', 'Opportunity to buy a local cafe franchise with established customer base.', 'franchise', 'Chicago, USA', '2026-08-01', 'open', '2026-03-28 08:16:09', '2026-03-28 08:16:09'),
(4, 3, 5, 'Tech Startup Co-founder', 'Seeking a technical co-founder for an AI-driven startup.', 'co-founder', 'Austin, USA', '2026-05-20', 'open', '2026-03-28 08:16:09', '2026-03-28 08:16:09'),
(5, 3, 6, 'Organic Farm Investment', 'Investors wanted for expanding organic farm operations.', 'investment', 'California, USA', '2026-09-30', 'open', '2026-03-28 08:16:09', '2026-03-28 08:16:09'),
(6, 3, 7, 'Marketing Agency Partnership', 'Looking for strategic partners to scale digital marketing agency.', 'partnership', 'Los Angeles, USA', '2026-06-15', 'open', '2026-03-28 08:16:09', '2026-03-28 08:16:09'),
(7, 3, 8, 'Mobile App Franchise', 'Sell and operate a mobile app-based service under existing brand.', 'franchise', 'Seattle, USA', '2026-07-31', 'draft', '2026-03-28 08:16:09', '2026-03-28 08:16:09'),
(8, 3, 9, 'Restaurant Co-founder', 'Seeking a co-founder for a new fusion cuisine restaurant.', 'co-founder', 'Miami, USA', '2026-08-20', 'open', '2026-03-28 08:16:09', '2026-03-28 08:16:09'),
(9, 3, 10, 'E-commerce Expansion Investment', 'Investors needed to scale online retail operations.', 'investment', 'Boston, USA', '2026-09-10', 'open', '2026-03-28 08:16:09', '2026-03-28 08:16:09'),
(10, 3, 11, 'Health Tech Partnership', 'Partnering opportunity in health tech wearable devices business.', 'partnership', 'Denver, USA', '2026-07-01', 'open', '2026-03-28 08:16:09', '2026-03-28 08:16:09');

-- --------------------------------------------------------

--
-- Table structure for table `private_messages`
--

CREATE TABLE `private_messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `body` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `private_messages`
--

INSERT INTO `private_messages` (`id`, `sender_id`, `receiver_id`, `subject`, `body`, `created_at`) VALUES
(1, 1, 3, 'd', 'Hy', '2026-03-28 09:09:38'),
(2, 3, 1, 'ds', 'Yes', '2026-03-28 09:12:56'),
(3, 2, 3, '', 'cc', '2026-03-29 06:57:05');

-- --------------------------------------------------------

--
-- Table structure for table `tutorials`
--

CREATE TABLE `tutorials` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` longtext DEFAULT NULL,
  `video_url` varchar(500) DEFAULT NULL,
  `duration_min` smallint(5) UNSIGNED DEFAULT NULL,
  `level` enum('beginner','intermediate','advanced') NOT NULL DEFAULT 'beginner',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tutorials`
--

INSERT INTO `tutorials` (`id`, `category_id`, `title`, `content`, `video_url`, `duration_min`, `level`, `created_at`, `updated_at`) VALUES
(2, 2, 'Introduction to Business Planning', 'Learn how to create a solid business plan step by step.', 'https://youtube.com/watch?v=vid1', 25, 'beginner', '2026-03-29 06:53:39', '2026-03-29 06:53:39'),
(3, 3, 'Marketing Fundamentals', 'Understand core marketing strategies for startups.', 'https://youtube.com/watch?v=vid2', 30, 'beginner', '2026-03-29 06:53:39', '2026-03-29 06:53:39'),
(4, 4, 'Advanced SEO Techniques', 'Deep dive into SEO optimization strategies.', 'https://youtube.com/watch?v=vid3', 40, 'advanced', '2026-03-29 06:53:39', '2026-03-29 06:53:39'),
(5, 5, 'Social Media Growth Hacks', 'Grow your audience using proven techniques.', 'https://youtube.com/watch?v=vid4', 35, 'intermediate', '2026-03-29 06:53:39', '2026-03-29 06:53:39'),
(6, 6, 'Startup Funding Guide', 'Learn how to pitch and secure investment.', 'https://youtube.com/watch?v=vid5', 45, 'advanced', '2026-03-29 06:53:39', '2026-03-29 06:53:39'),
(7, 7, 'Freelancing for Beginners', 'Start earning online with freelancing.', 'https://youtube.com/watch?v=vid6', 20, 'beginner', '2026-03-29 06:53:39', '2026-03-29 06:53:39'),
(8, 8, 'E-commerce Setup Guide', 'Build and launch your online store.', 'https://youtube.com/watch?v=vid7', 50, 'intermediate', '2026-03-29 06:53:39', '2026-03-29 06:53:39'),
(9, 9, 'UI/UX Design Basics', 'Design principles for better user experience.', 'https://youtube.com/watch?v=vid8', 30, 'beginner', '2026-03-29 06:53:39', '2026-03-29 06:53:39'),
(10, 10, 'Mobile App Development Intro', 'Build your first mobile app.', 'https://youtube.com/watch?v=vid9', 60, 'intermediate', '2026-03-29 06:53:39', '2026-03-29 06:53:39'),
(11, 11, 'Python for Entrepreneurs', 'Automate business tasks using Python.', 'https://youtube.com/watch?v=vid10', 45, 'intermediate', '2026-03-29 06:53:39', '2026-03-29 06:53:39'),
(12, 12, 'Data Analysis with Excel', 'Analyze business data effectively.', 'https://youtube.com/watch?v=vid11', 35, 'beginner', '2026-03-29 06:53:39', '2026-03-29 06:53:39'),
(13, 13, 'Cybersecurity Essentials', 'Protect your business systems.', 'https://youtube.com/watch?v=vid12', 30, 'beginner', '2026-03-29 06:53:39', '2026-03-29 06:53:39'),
(14, 14, 'Cloud Computing Basics', 'Understand AWS and cloud services.', 'https://youtube.com/watch?v=vid13', 40, 'intermediate', '2026-03-29 06:53:39', '2026-03-29 06:53:39'),
(15, 15, 'Content Creation Strategy', 'Create engaging content for your brand.', 'https://youtube.com/watch?v=vid14', 25, 'beginner', '2026-03-29 06:53:39', '2026-03-29 06:53:39'),
(16, 16, 'Affiliate Marketing Guide', 'Earn passive income online.', 'https://youtube.com/watch?v=vid15', 30, 'intermediate', '2026-03-29 06:53:39', '2026-03-29 06:53:39'),
(17, 17, 'Public Speaking Skills', 'Improve your communication skills.', 'https://youtube.com/watch?v=vid16', 20, 'beginner', '2026-03-29 06:53:39', '2026-03-29 06:53:39'),
(18, 18, 'Business Analytics Advanced', 'Use analytics for decision making.', 'https://youtube.com/watch?v=vid17', 50, 'advanced', '2026-03-29 06:53:39', '2026-03-29 06:53:39'),
(19, 19, 'Startup Legal Basics', 'Understand contracts and compliance.', 'https://youtube.com/watch?v=vid18', 25, 'beginner', '2026-03-29 06:53:39', '2026-03-29 06:53:39'),
(20, 20, 'Networking for Success', 'Build strong professional connections.', 'https://youtube.com/watch?v=vid19', 20, 'beginner', '2026-03-29 06:53:39', '2026-03-29 06:53:39'),
(21, 21, 'Scaling Your Startup', 'Strategies to grow your business.', 'https://youtube.com/watch?v=vid20', 45, 'advanced', '2026-03-29 06:53:39', '2026-03-29 06:53:39');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `email` varchar(191) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('user','agent','admin') NOT NULL DEFAULT 'user',
  `status` enum('active','inactive','banned') NOT NULL DEFAULT 'active',
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password_hash`, `role`, `status`, `phone`, `created_at`, `updated_at`) VALUES
(1, 'NASIR ABBAS', 'nasiryt.827@gmail.com', '$2y$10$QsZK.TchAAdW5tUBjvPCi.QlDMfxGMsYSC3Jr9U17iL1ZlUZTlJr6', 'user', 'active', '03176526827', '2026-03-28 07:44:30', '2026-03-28 07:44:30'),
(2, 'Admin', 'admin@gmail.com', '$2y$10$RAzw5hVCsSt8R0TA.5DOru0qkT/Gr9fLQp6m3OMwGBa3rEiIia4sa', 'admin', 'active', '00000000000', '2026-03-28 07:48:11', '2026-03-28 07:48:11'),
(3, 'Agent', 'agent@gmail.com', '$2y$10$pb.z8wdGHJ0zWWxxTEgPaexFtCBmRrzJ8GKImXV9DvUEokkT9ia/2', 'agent', 'active', '+92 3120558090', '2026-03-28 07:51:14', '2026-03-28 07:57:01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `agent_profiles`
--
ALTER TABLE `agent_profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_unique` (`user_id`);

--
-- Indexes for table `business_categories`
--
ALTER TABLE `business_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `business_ideas`
--
ALTER TABLE `business_ideas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_idea_agent` (`agent_id`),
  ADD KEY `fk_idea_category` (`category_id`);

--
-- Indexes for table `business_resources`
--
ALTER TABLE `business_resources`
  ADD PRIMARY KEY (`resource_id`),
  ADD KEY `fk_resource_agent` (`agent_id`),
  ADD KEY `fk_resource_category` (`category_id`);

--
-- Indexes for table `business_templates`
--
ALTER TABLE `business_templates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_template_agent` (`agent_id`),
  ADD KEY `fk_template_category` (`category_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_feedback_agent` (`agent_id`),
  ADD KEY `fk_feedback_user` (`user_id`);

--
-- Indexes for table `forum_replies`
--
ALTER TABLE `forum_replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_reply_thread` (`thread_id`),
  ADD KEY `fk_reply_user` (`user_id`);

--
-- Indexes for table `forum_threads`
--
ALTER TABLE `forum_threads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_thread_user` (`user_id`);

--
-- Indexes for table `opportunities`
--
ALTER TABLE `opportunities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `private_messages`
--
ALTER TABLE `private_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_msg_sender` (`sender_id`),
  ADD KEY `fk_msg_receiver` (`receiver_id`);

--
-- Indexes for table `tutorials`
--
ALTER TABLE `tutorials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_users_email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `agent_profiles`
--
ALTER TABLE `agent_profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `business_categories`
--
ALTER TABLE `business_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `business_ideas`
--
ALTER TABLE `business_ideas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `business_resources`
--
ALTER TABLE `business_resources`
  MODIFY `resource_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `business_templates`
--
ALTER TABLE `business_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `forum_replies`
--
ALTER TABLE `forum_replies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `forum_threads`
--
ALTER TABLE `forum_threads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `opportunities`
--
ALTER TABLE `opportunities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `private_messages`
--
ALTER TABLE `private_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tutorials`
--
ALTER TABLE `tutorials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `agent_profiles`
--
ALTER TABLE `agent_profiles`
  ADD CONSTRAINT `fk_agent_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `business_ideas`
--
ALTER TABLE `business_ideas`
  ADD CONSTRAINT `fk_idea_agent` FOREIGN KEY (`agent_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_idea_category` FOREIGN KEY (`category_id`) REFERENCES `business_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `business_resources`
--
ALTER TABLE `business_resources`
  ADD CONSTRAINT `fk_resource_agent` FOREIGN KEY (`agent_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_resource_category` FOREIGN KEY (`category_id`) REFERENCES `business_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `business_templates`
--
ALTER TABLE `business_templates`
  ADD CONSTRAINT `fk_template_agent` FOREIGN KEY (`agent_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_template_category` FOREIGN KEY (`category_id`) REFERENCES `business_categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `fk_feedback_agent` FOREIGN KEY (`agent_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_feedback_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `forum_replies`
--
ALTER TABLE `forum_replies`
  ADD CONSTRAINT `fk_reply_thread` FOREIGN KEY (`thread_id`) REFERENCES `forum_threads` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_reply_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `forum_threads`
--
ALTER TABLE `forum_threads`
  ADD CONSTRAINT `fk_thread_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `private_messages`
--
ALTER TABLE `private_messages`
  ADD CONSTRAINT `fk_msg_receiver` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_msg_sender` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tutorials`
--
ALTER TABLE `tutorials`
  ADD CONSTRAINT `tutorials_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `business_categories` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
