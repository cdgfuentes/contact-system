-- demo data, login with demo@example.com / password123
-- mysql -u root -p contact_system < database/seed.sql

USE contact_system;

INSERT INTO users (user_id, username, email, password) VALUES
  (1, 'demo', 'demo@example.com', '$2y$10$jnXQVXQKcJ.XcKLNgx7iluZV.yDFolfwLLPhDs5OKl9bMGo9cqNAC');

INSERT INTO contacts (user_id, name, company, phone_number, email) VALUES
  (1, 'Maria Santos',    'Northwind Traders', '+63 917 555 0142', 'maria.santos@example.com'),
  (1, 'James Tan',       'Blue Harbor Inc.',  '+63 918 555 0187', 'james.tan@example.com'),
  (1, 'Angela Reyes',    'Acme Logistics',    '+63 920 555 0119', 'angela.reyes@example.com'),
  (1, 'Paolo Cruz',      'Summit Labs',       '+63 915 555 0163', 'paolo.cruz@example.com'),
  (1, 'Grace Lim',       'Brightline Media',  '+63 916 555 0108', 'grace.lim@example.com'),
  (1, 'Daniel Villanueva', 'Northwind Traders', '+63 919 555 0174', 'daniel.v@example.com');
