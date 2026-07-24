# 📚 Study Planner PHP - Planificateur d'Étude

A complete **study planning application** built with **PHP, HTML, CSS, JavaScript, and Bootstrap**. Organize your learning journey with multiple courses, modules, daily goals, and progress tracking.

## ✨ Features

✅ **Multiple Learning Plans** - Create plans for languages and programming courses
✅ **Module Organization** - Break courses into manageable modules/chapters
✅ **Exercise Management** - Track exercises within each module
✅ **Study Sessions** - Log your daily study sessions with notes
✅ **Daily Goals** - Set and track daily study targets
✅ **Progress Tracking** - Visual progress bars and statistics
✅ **Bilingual Interface** - French/English language support
✅ **Responsive Design** - Works on desktop, tablet, and mobile
✅ **Bootstrap 5** - Professional UI with modern components
✅ **MySQL Database** - Persistent data storage
✅ **Beautiful Design** - Custom color scheme (purple, red, green, yellow, black, white)

## 🚀 Installation

### 1. Database Setup

**Option A: Using phpMyAdmin**
1. Open phpMyAdmin
2. Create a new database named `study_planner`
3. Import the `database.sql` file

**Option B: Using Command Line**
```bash
mysql -u root -p < database.sql
```

### 2. Configure Database

Edit `config.php` and update the credentials:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'study_planner');
```

### 3. Upload Files

Upload all files to your web server:
```
/study-planner-php/
├── index.php
├── config.php
├── css/
│   └── style.css
├── js/
│   ├── script.js
│   └── app.js
├── api/
│   ├── plans.php
│   ├── modules.php
│   ├── sessions.php
│   └── goals.php
└── database.sql
```

### 4. Access Application

Visit in your browser:
```
http://localhost/study-planner-php/
```

Or if using a different path:
```
http://yourdomain.com/study-planner-php/
```

## 📁 File Structure

```
study-planner-php/
├── index.php                 # Main application page (HTML)
├── config.php               # Database configuration
├── database.sql             # Database schema
├── css/
│   └── style.css            # Custom styles
├── js/
│   ├── script.js            # Utilities & translations
│   └── app.js               # Application logic
└── api/
    ├── plans.php            # Study Plans API
    ├── modules.php          # Modules API
    ├── sessions.php         # Study Sessions API
    └── goals.php            # Daily Goals API
```

## 🎯 How to Use

### 1. Create a Learning Plan
- Go to **Learning Plans** tab
- Fill in plan name, language/subject, and description
- Click **Create Plan**

**Examples:**
- English Learning (10 min/day)
- Python Programming (60 min/day)
- Korean Vocabulary (20 min/day)

### 2. Add Modules
- Go to **Modules & Exercises** tab
- Select a plan from the sidebar
- Add modules with:
  - Title (e.g., "Module 1: Basics")
  - Duration (estimated time)
  - Description
  - Order (sequence)

**Example Structure:**
```
English Learning
├── Module 1: Pronunciation (10 min)
├── Module 2: Vocabulary (15 min)
├── Module 3: Listening (20 min)
└── Module 4: Speaking (15 min)
```

### 3. Log Study Sessions
- Go to **Study Log** tab
- Select a plan and date
- Enter actual study time (duration)
- Add optional notes about what you studied
- Click **Log Session**

### 4. Set Daily Goals
- Go to **Daily Goals** tab
- Select a plan
- Set target duration (e.g., 30 minutes)
- Track progress in real-time as you log sessions

### 5. Monitor Progress
- **Dashboard** shows overall statistics
- **Progress bars** for each plan
- **Study time today** counter
- **Average progress** across all plans

## 🔧 API Endpoints

### Plans API (`api/plans.php`)
```
GET  /api/plans.php?action=list          # Get all plans
GET  /api/plans.php?action=get&id=1      # Get single plan
POST /api/plans.php?action=create        # Create plan
DEL  /api/plans.php?action=delete&id=1   # Delete plan
```

### Modules API (`api/modules.php`)
```
GET  /api/modules.php?action=list&plan_id=1     # Get plan's modules
GET  /api/modules.php?action=get&id=1           # Get single module
POST /api/modules.php?action=create             # Create module
POST /api/modules.php?action=update&id=1        # Update module
DEL  /api/modules.php?action=delete&id=1        # Delete module
```

### Sessions API (`api/sessions.php`)
```
GET  /api/sessions.php?action=list&plan_id=1    # Get plan's sessions
GET  /api/sessions.php?action=today&plan_id=1   # Get today's sessions
POST /api/sessions.php?action=create            # Log study session
DEL  /api/sessions.php?action=delete&id=1       # Delete session
```

### Goals API (`api/goals.php`)
```
GET  /api/goals.php?action=list&plan_id=1       # Get plan's goals
GET  /api/goals.php?action=today&plan_id=1      # Get today's goal
POST /api/goals.php?action=create               # Set daily goal
POST /api/goals.php?action=update&id=1          # Update goal
DEL  /api/goals.php?action=delete&id=1          # Delete goal
```

## 🎨 Customization

### Change Colors

Edit `css/style.css`:
```css
:root {
    --primary-red: #E63946;
    --primary-purple: #7209B7;
    --primary-black: #1A1A1A;
    --primary-white: #FFFFFF;
    --primary-beige: #F5DEB3;
    --primary-green: #2A9D8F;
    --primary-yellow: #FFD60A;
    --primary-gray: #6C757D;
}
```

### Change Language

Default translations in `js/script.js`:
```javascript
const translations = {
    en: { /* English translations */ },
    fr: { /* French translations */ }
};
```

## 🌐 Requirements

- **PHP 7.4+**
- **MySQL 5.7+ or MariaDB**
- **Web Server** (Apache, Nginx, etc.)
- **Bootstrap 5** (CDN)
- **Font Awesome** (CDN)

## 📊 Database Schema

### Tables
1. **study_plans** - Learning plans
2. **modules** - Course modules
3. **exercises** - Exercises within modules
4. **study_sessions** - Daily study logs
5. **study_logs** - Session activity details
6. **daily_goals** - Daily study targets

## 🐛 Troubleshooting

### Database Connection Failed
- Check `config.php` credentials
- Ensure MySQL is running
- Verify database exists

### Files Not Found (404)
- Check file paths are correct
- Ensure files are uploaded to correct directory
- Verify web server permissions

### Styles Not Loading
- Clear browser cache
- Check Bootstrap CDN is accessible
- Verify `css/style.css` is in correct location

### API Not Responding
- Check PHP error logs
- Verify database connection
- Ensure POST/GET requests are correct

## 💡 Tips for Best Results

1. **Be Realistic** - Set achievable daily goals
2. **Stay Consistent** - Log sessions every day
3. **Break It Down** - Use modules to organize content
4. **Track Progress** - Review statistics regularly
5. **Adjust Goals** - Modify targets based on progress

## 🚀 Example Workflow

**Day 1:**
1. Create "English Learning" plan
2. Add 4 modules
3. Set daily goal: 30 minutes
4. Study for 30 minutes
5. Log session

**Day 2:**
1. Check dashboard (25% complete)
2. Study different module (45 min)
3. Log session
4. See progress increase

**Week 1 Review:**
- Total study time: 165 minutes
- Plans completed: 25%
- Modules done: 2/8
- Average daily: 23.5 minutes

## 📱 Browser Support

- ✅ Chrome/Chromium
- ✅ Firefox
- ✅ Safari
- ✅ Edge
- ✅ Mobile browsers

## 📄 License

MIT - Free to use and modify

## 🤝 Contributing

Feel free to customize and extend the application!

---

**Start your learning journey today! 🚀**

*Maîtrisez votre parcours d'apprentissage ! 📚*
