-- DelphianLogic in Action — seed data (matches the reference design).
-- Runs after schema.sql (alphabetical order in docker-entrypoint-initdb.d).

SET NAMES utf8mb4;

INSERT INTO tabs (id, name, slug, icon, sort_order) VALUES
  (1, 'Learning',      'learning',      'DL-learning.svg',      1),
  (2, 'Technology',    'technology',    'DL-technology.svg',    2),
  (3, 'Communication', 'communication', 'DL-communication.svg', 3);

-- Each tab is its own slider with 3 slides (3 dots in the design).
-- Slides cycle through the 3 supplied images so the Column 2 <-> Column 3 sync is visible.
INSERT INTO slides (tab_id, category_label, title, link_text, link_url, image_path, sort_order) VALUES
  -- Learning
  (1, 'DIGITAL LEARNING INFRASTRUCTURE', 'Usability enhancement and Training for Transaction Portal for Customers', 'Learn More', '#', 'uploads/DL-Learning-1.jpg',   1),
  (1, 'DIGITAL LEARNING INFRASTRUCTURE', 'Interactive Courseware and Continuous Assessment Platforms',             'Learn More', '#', 'uploads/DL-Technology.jpg',   2),
  (1, 'DIGITAL LEARNING INFRASTRUCTURE', 'Learning Management System Modernization and Rollout',                   'Learn More', '#', 'uploads/DL-Communication.jpg', 3),
  -- Technology
  (2, 'TECHNOLOGY SOLUTIONS', 'Cloud Migration and Application Modernization at Scale',          'Learn More', '#', 'uploads/DL-Technology.jpg',   1),
  (2, 'TECHNOLOGY SOLUTIONS', 'Data Engineering and Real-time Analytics Pipelines',             'Learn More', '#', 'uploads/DL-Learning-1.jpg',   2),
  (2, 'TECHNOLOGY SOLUTIONS', 'Secure DevOps Automation and Infrastructure as Code',            'Learn More', '#', 'uploads/DL-Communication.jpg', 3),
  -- Communication
  (3, 'COMMUNICATION SERVICES', 'Omnichannel Customer Engagement and Messaging',                'Learn More', '#', 'uploads/DL-Communication.jpg', 1),
  (3, 'COMMUNICATION SERVICES', 'Global Collaboration and Unified Communications',              'Learn More', '#', 'uploads/DL-Technology.jpg',   2),
  (3, 'COMMUNICATION SERVICES', 'Brand Storytelling and Content Distribution Strategy',         'Learn More', '#', 'uploads/DL-Learning-1.jpg',   3);
