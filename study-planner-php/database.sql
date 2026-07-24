-- Create database
CREATE DATABASE IF NOT EXISTS study_planner;
USE study_planner;

-- Study Plans Table
CREATE TABLE study_plans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    language VARCHAR(50),
    color VARCHAR(20) DEFAULT 'purple',
    total_duration INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Modules Table
CREATE TABLE modules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    study_plan_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    `order` INT DEFAULT 0,
    duration INT NOT NULL,
    completed BOOLEAN DEFAULT FALSE,
    completed_date DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (study_plan_id) REFERENCES study_plans(id) ON DELETE CASCADE
);

-- Exercises Table
CREATE TABLE exercises (
    id INT AUTO_INCREMENT PRIMARY KEY,
    module_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    duration INT NOT NULL,
    difficulty VARCHAR(20) DEFAULT 'medium',
    completed BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (module_id) REFERENCES modules(id) ON DELETE CASCADE
);

-- Study Sessions Table
CREATE TABLE study_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    study_plan_id INT NOT NULL,
    session_date DATE NOT NULL,
    duration INT NOT NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (study_plan_id) REFERENCES study_plans(id) ON DELETE CASCADE
);

-- Study Logs Table
CREATE TABLE study_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    study_session_id INT NOT NULL,
    module_id INT,
    exercise_id INT,
    duration_spent INT NOT NULL,
    activity_type VARCHAR(50),
    status VARCHAR(20) DEFAULT 'in_progress',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (study_session_id) REFERENCES study_sessions(id) ON DELETE CASCADE,
    FOREIGN KEY (module_id) REFERENCES modules(id) ON DELETE SET NULL,
    FOREIGN KEY (exercise_id) REFERENCES exercises(id) ON DELETE SET NULL
);

-- Daily Goals Table
CREATE TABLE daily_goals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    study_plan_id INT NOT NULL,
    goal_date DATE NOT NULL,
    target_duration INT NOT NULL,
    actual_duration INT DEFAULT 0,
    achieved BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (study_plan_id) REFERENCES study_plans(id) ON DELETE CASCADE
);

-- Create indexes for better performance
CREATE INDEX idx_plan_id ON modules(study_plan_id);
CREATE INDEX idx_module_id ON exercises(module_id);
CREATE INDEX idx_session_plan ON study_sessions(study_plan_id);
CREATE INDEX idx_session_date ON study_sessions(session_date);
CREATE INDEX idx_goal_plan ON daily_goals(study_plan_id);
CREATE INDEX idx_goal_date ON daily_goals(goal_date);