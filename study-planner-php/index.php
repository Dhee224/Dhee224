<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Study Planner - Planificateur d'Étude</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Language Switcher -->
    <div class="language-switcher">
        <button class="lang-btn active" onclick="changeLanguage('en')">English</button>
        <button class="lang-btn" onclick="changeLanguage('fr')">Français</button>
    </div>

    <!-- Header -->
    <div class="header-custom text-center">
        <div class="container">
            <h1 data-i18n="mainTitle">Study Planner</h1>
            <p data-i18n="mainSubtitle">Master Your Learning Journey</p>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light navbar-custom">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="nav nav-tabs w-100 border-0" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="dashboard-tab" data-bs-toggle="tab" data-bs-target="#dashboard" type="button" role="tab" data-i18n="tab-dashboard">Dashboard</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="plans-tab" data-bs-toggle="tab" data-bs-target="#plans" type="button" role="tab" data-i18n="tab-plans">Learning Plans</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="modules-tab" data-bs-toggle="tab" data-bs-target="#modules" type="button" role="tab" data-i18n="tab-modules">Modules</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="study-log-tab" data-bs-toggle="tab" data-bs-target="#study-log" type="button" role="tab" data-i18n="tab-study-log">Study Log</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="goals-tab" data-bs-toggle="tab" data-bs-target="#goals" type="button" role="tab" data-i18n="tab-goals">Daily Goals</button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container my-5" data-bs-toast-container>
        <div class="tab-content">
            <!-- Dashboard Tab -->
            <div class="tab-pane fade show active" id="dashboard" role="tabpanel">
                <h2 class="mb-4 text-purple" data-i18n="tab-dashboard">Dashboard</h2>
                
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <div class="stat-box">
                            <div class="stat-number" id="totalPlans">0</div>
                            <div class="stat-label" data-i18n="label-total-plans">Learning Plans</div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="stat-box">
                            <div class="stat-number" id="studyTimeToday">0</div>
                            <div class="stat-label" data-i18n="label-study-time">Study Time (min)</div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="stat-box">
                            <div class="stat-number" id="goalsAchieved">0</div>
                            <div class="stat-label" data-i18n="label-goals-achieved">Goals Achieved</div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="stat-box">
                            <div class="stat-number" id="avgProgress">0%</div>
                            <div class="stat-label" data-i18n="label-avg-progress">Average Progress</div>
                        </div>
                    </div>
                </div>

                <h3 class="mb-3 text-purple">📚 Your Learning Plans</h3>
                <div id="dashboardPlans"></div>
            </div>

            <!-- Plans Tab -->
            <div class="tab-pane fade" id="plans" role="tabpanel">
                <h2 class="mb-4 text-purple" data-i18n="tab-plans">Learning Plans</h2>
                
                <div class="card card-custom mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">➕ Create New Plan</h5>
                        <form id="createPlanForm" onsubmit="createPlan(event)">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Plan Name</label>
                                    <input type="text" class="form-control form-control-custom" id="planName" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Language/Subject</label>
                                    <select class="form-select form-control-custom" id="planLanguage">
                                        <option value="english">English</option>
                                        <option value="korean">Korean</option>
                                        <option value="chinese">Chinese</option>
                                        <option value="programming">Programming</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Color</label>
                                    <select class="form-select form-control-custom" id="planColor">
                                        <option value="purple">Purple</option>
                                        <option value="red">Red</option>
                                        <option value="green">Green</option>
                                        <option value="yellow">Yellow</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control form-control-custom" id="planDescription" rows="2"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary-custom" data-i18n="btn-create-plan">Create Plan</button>
                        </form>
                    </div>
                </div>

                <h3 class="mb-3 text-purple">📋 Existing Plans</h3>
                <div id="plansList"></div>
            </div>

            <!-- Modules Tab -->
            <div class="tab-pane fade" id="modules" role="tabpanel">
                <h2 class="mb-4 text-purple" data-i18n="tab-modules">Modules & Exercises</h2>
                
                <div class="row">
                    <div class="col-md-3">
                        <div class="sidebar-custom mb-4">
                            <div class="sidebar-title">📚 Select Plan</div>
                            <div id="planSelector"></div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="card card-custom mb-4">
                            <div class="card-body">
                                <h5 class="card-title mb-3">➕ Add Module</h5>
                                <form id="createModuleForm" onsubmit="createModule(event)">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Module Title</label>
                                            <input type="text" class="form-control form-control-custom" id="moduleTitle" required>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Duration (min)</label>
                                            <input type="number" class="form-control form-control-custom" id="moduleDuration" min="10" value="30" required>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Order</label>
                                            <input type="number" class="form-control form-control-custom" id="moduleOrder" min="1" value="1" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control form-control-custom" id="moduleDescription" rows="2"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary-custom" data-i18n="btn-add-module">Add Module</button>
                                </form>
                            </div>
                        </div>

                        <h3 class="mb-3 text-purple">📖 Modules</h3>
                        <div id="modulesList"></div>
                    </div>
                </div>
            </div>

            <!-- Study Log Tab -->
            <div class="tab-pane fade" id="study-log" role="tabpanel">
                <h2 class="mb-4 text-purple" data-i18n="tab-study-log">Study Log</h2>
                
                <div class="card card-custom mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">📝 Log Study Session</h5>
                        <form id="createSessionForm" onsubmit="createSession(event)">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Learning Plan</label>
                                    <select class="form-select form-control-custom" id="sessionPlan" required></select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Date</label>
                                    <input type="date" class="form-control form-control-custom" id="sessionDate" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Duration (min)</label>
                                    <input type="number" class="form-control form-control-custom" id="sessionDuration" min="5" value="30" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Notes</label>
                                <textarea class="form-control form-control-custom" id="sessionNotes" rows="2"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary-custom" data-i18n="btn-log-session">Log Session</button>
                        </form>
                    </div>
                </div>

                <h3 class="mb-3 text-purple">📋 Recent Sessions</h3>
                <div id="sessionsList"></div>
            </div>

            <!-- Goals Tab -->
            <div class="tab-pane fade" id="goals" role="tabpanel">
                <h2 class="mb-4 text-purple" data-i18n="tab-goals">Daily Goals</h2>
                
                <div class="card card-custom mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">🎯 Set Daily Goal</h5>
                        <form id="createGoalForm" onsubmit="createGoal(event)">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Learning Plan</label>
                                    <select class="form-select form-control-custom" id="goalPlan" required></select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Date</label>
                                    <input type="date" class="form-control form-control-custom" id="goalDate" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Target Duration (min)</label>
                                    <input type="number" class="form-control form-control-custom" id="targetDuration" min="10" value="60" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary-custom" data-i18n="btn-set-goal">Set Goal</button>
                        </form>
                    </div>
                </div>

                <h3 class="mb-3 text-purple">🎯 Today's Goals</h3>
                <div id="goalsList"></div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom Scripts -->
    <script src="js/script.js"></script>
    <script src="js/app.js"></script>
</body>
</html>
