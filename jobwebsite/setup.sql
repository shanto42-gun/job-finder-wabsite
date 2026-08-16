-- =============================================
-- Job Platform Database Setup
-- Run this in phpMyAdmin or MySQL CLI
-- =============================================

CREATE DATABASE IF NOT EXISTS job_platform CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE job_platform;

-- =============================================
-- Users Table
-- =============================================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(150) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =============================================
-- Admin Users Table
-- =============================================
CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =============================================
-- Jobs Table
-- =============================================
CREATE TABLE IF NOT EXISTS jobs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    company VARCHAR(255) NOT NULL,
    location VARCHAR(255) NOT NULL,
    job_type ENUM('Full-time','Part-time','Remote','Contract','Internship') NOT NULL DEFAULT 'Full-time',
    salary VARCHAR(100) DEFAULT NULL,
    description TEXT NOT NULL,
    skills TEXT DEFAULT NULL,
    experience VARCHAR(100) DEFAULT NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =============================================
-- Applications Table
-- =============================================
CREATE TABLE IF NOT EXISTS applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    job_id INT NOT NULL,
    user_id INT DEFAULT NULL,
    full_name VARCHAR(150) NOT NULL,
    email VARCHAR(255) NOT NULL,
    whatsapp VARCHAR(20) NOT NULL,
    message TEXT DEFAULT NULL,
    applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =============================================
-- Seed Admin User (password: admin123)
-- =============================================
INSERT INTO admin_users (username, password) VALUES
('YouBTech', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- =============================================
-- Seed Sample Jobs
-- =============================================
INSERT INTO jobs (title, company, location, job_type, salary, description, skills, experience) VALUES
(
    'Senior Frontend Developer',
    'TechNova Inc.',
    'Remote (Worldwide)',
    'Remote',
    '$5,000 – $8,000/mo',
    'We are looking for a passionate Senior Frontend Developer to join our distributed team. You will be responsible for building high-performance web applications, collaborating with designers and backend engineers, and helping shape the technical direction of our frontend architecture.\n\nYou will work on exciting products used by millions of users worldwide. We value clean code, great UX, and continuous learning.',
    'React, TypeScript, Next.js, Tailwind CSS, GraphQL, REST APIs, Git',
    '3+ years'
),
(
    'Full Stack PHP Developer',
    'CodeCraft Solutions',
    'Lahore, Pakistan',
    'Full-time',
    'PKR 150,000 – 250,000/mo',
    'CodeCraft Solutions is hiring a Full Stack PHP Developer to build and maintain scalable web applications for our enterprise clients. You will work across the full stack using PHP, MySQL, and modern JavaScript frameworks.\n\nThis role offers great growth potential and a collaborative, supportive team environment.',
    'PHP, Laravel, MySQL, JavaScript, Vue.js, REST APIs, Docker',
    '2+ years'
),
(
    'UI/UX Designer',
    'PixelDream Studio',
    'Karachi, Pakistan',
    'Part-time',
    'PKR 80,000 – 120,000/mo',
    'PixelDream Studio is seeking a creative UI/UX Designer who can turn complex problems into elegant, user-friendly interfaces. You will own the design process end-to-end, from wireframes to pixel-perfect mockups.\n\nWe are a small, passionate team building digital products that users love.',
    'Figma, Adobe XD, Prototyping, User Research, Design Systems, HTML/CSS',
    '1+ years'
),
(
    'Mobile App Developer (Flutter)',
    'AppSphere Technologies',
    'Remote (Pakistan)',
    'Remote',
    '$2,000 – $4,000/mo',
    'AppSphere Technologies is looking for a Flutter Developer to build beautiful cross-platform mobile apps for iOS and Android. You will work closely with our product team to deliver fast, smooth, and delightful mobile experiences.\n\nFlexible hours, fully remote, and a fantastic team culture.',
    'Flutter, Dart, Firebase, REST APIs, Git, State Management (GetX/Riverpod)',
    '1+ years'
),
(
    'Data Analyst',
    'Insightify Corp.',
    'Islamabad, Pakistan',
    'Full-time',
    'PKR 120,000 – 180,000/mo',
    'Insightify Corp. is looking for a Data Analyst to help us make sense of large datasets and turn data into actionable business insights. You will build dashboards, run analyses, and present findings to stakeholders.\n\nGreat opportunity to work with cutting-edge data infrastructure and make a real business impact.',
    'Python, SQL, Power BI, Excel, Pandas, NumPy, Data Visualization',
    '1+ years'
),
(
    'DevOps Engineer',
    'CloudPeak Systems',
    'Remote (Worldwide)',
    'Contract',
    '$4,000 – $7,000/mo',
    'CloudPeak Systems needs an experienced DevOps Engineer to manage our cloud infrastructure on AWS, automate CI/CD pipelines, and ensure platform reliability and security.\n\nThis is a contract position with possibility of going full-time. You will have significant autonomy and ownership over our infrastructure.',
    'AWS, Docker, Kubernetes, CI/CD, Terraform, Linux, Bash, Python',
    '3+ years'
);

-- =============================================
-- Seed Sample Users (password: password123)
-- =============================================
INSERT INTO users (full_name, email, password, phone) VALUES
('Ahmed Khan', 'ahmed@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+92 300 1234567'),
('Sara Ali', 'sara@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+92 321 9876543');

-- =============================================
-- Seed Sample Applications
-- =============================================
INSERT INTO applications (job_id, user_id, full_name, email, whatsapp, message) VALUES
(1, 1, 'Ahmed Khan', 'ahmed@example.com', '+92 300 1234567', 'I am very interested in this position and believe my 4 years of React experience makes me a great fit.'),
(2, 2, 'Sara Ali', 'sara@example.com', '+92 321 9876543', 'I have been working with PHP and Laravel for 3 years and would love to join your team.');
