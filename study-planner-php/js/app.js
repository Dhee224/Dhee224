// Set today's date by default
document.addEventListener('DOMContentLoaded', function() {
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('sessionDate').value = today;
    document.getElementById('goalDate').value = today;
    
    // Load initial data
    loadDashboard();
    loadPlans();
    populatePlanSelectors();
});

let selectedPlanId = null;
let allPlans = [];

// ========== STUDY PLANS ==========
async function loadPlans() {
    const response = await API.get('plans.php?action=list');
    if (response) {
        allPlans = response;
        renderPlans();
        updatePlanSelectors();
        updateDashboard();
    }
}

async function createPlan(event) {
    event.preventDefault();
    
    const data = {
        name: document.getElementById('planName').value,
        description: document.getElementById('planDescription').value,
        language: document.getElementById('planLanguage').value,
        color: document.getElementById('planColor').value
    };
    
    const response = await API.post('plans.php?action=create', data);
    
    if (response && response.success) {
        showToast(t('success-msg'), 'success');
        document.getElementById('createPlanForm').reset();
        loadPlans();
    } else {
        showToast(t('error-msg'), 'danger');
    }
}

function renderPlans() {
    const container = document.getElementById('plansList');
    
    if (allPlans.length === 0) {
        container.innerHTML = `
            <div class="empty-state">
                <div class="empty-state-icon">📚</div>
                <div class="empty-state-title" data-i18n="no-plans">No learning plans yet</div>
            </div>
        `;
        return;
    }
    
    container.innerHTML = allPlans.map(plan => `
        <div class="card card-custom border-${plan.color}">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="card-title">${plan.name}</h5>
                        <p class="card-text text-muted">${plan.description || ''}</p>
                    </div>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-sm btn-secondary-custom" onclick="selectPlanForModules(${plan.id})" data-i18n="btn-edit">Edit</button>
                        <button type="button" class="btn btn-sm btn-danger-custom" onclick="deletePlan(${plan.id})" data-i18n="btn-delete">Delete</button>
                    </div>
                </div>
                <div class="progress-custom mb-2">
                    <div class="progress-bar-custom" style="width: ${plan.progress}%"></div>
                </div>
                <small class="text-muted">${plan.progress}% Complete • ${plan.modules_count} Modules</small>
            </div>
        </div>
    `).join('');
}

async function deletePlan(planId) {
    if (confirmDelete(allPlans.find(p => p.id === planId).name)) {
        const response = await API.delete(`plans.php?action=delete&id=${planId}`);
        if (response && response.success) {
            showToast(t('success-msg'), 'success');
            loadPlans();
        } else {
            showToast(t('error-msg'), 'danger');
        }
    }
}

// ========== MODULES ==========
async function selectPlanForModules(planId) {
    selectedPlanId = planId;
    const planName = allPlans.find(p => p.id === planId)?.name || '';
    
    // Highlight selected plan
    document.querySelectorAll('#planSelector .list-group-item').forEach(item => {
        item.classList.remove('active');
    });
    document.querySelector(`[data-plan-id="${planId}"]`)?.classList.add('active');
    
    loadModules();
}

function renderPlanSelector() {
    const container = document.getElementById('planSelector');
    
    container.innerHTML = allPlans.map(plan => `
        <div class="list-group-item list-group-item-action cursor-pointer" 
             data-plan-id="${plan.id}" 
             onclick="selectPlanForModules(${plan.id})">
            <div class="fw-bold">${plan.name}</div>
            <small class="text-muted">${plan.modules_count} modules</small>
        </div>
    `).join('');
}

async function loadModules() {
    if (!selectedPlanId) {
        document.getElementById('modulesList').innerHTML = `
            <div class="empty-state">
                <div class="empty-state-icon">📖</div>
                <div class="empty-state-title">Select a plan first</div>
            </div>
        `;
        return;
    }
    
    const response = await API.get(`modules.php?action=list&plan_id=${selectedPlanId}`);
    if (response) {
        renderModules(response);
    }
}

function renderModules(modules) {
    const container = document.getElementById('modulesList');
    
    if (!modules || modules.length === 0) {
        container.innerHTML = `
            <div class="empty-state">
                <div class="empty-state-icon">📖</div>
                <div class="empty-state-title" data-i18n="no-modules">No modules yet</div>
            </div>
        `;
        return;
    }
    
    container.innerHTML = modules.map(module => `
        <div class="card card-custom ${module.completed ? 'completed' : ''}">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div style="flex: 1;">
                        <h6 class="card-title mb-1">${module.title}</h6>
                        <small class="text-muted">${module.duration} ${t('minutes')} • ${module.exercises_count || 0} exercises</small>
                        ${module.description ? `<p class="card-text small mt-2">${module.description}</p>` : ''}
                    </div>
                    <div class="d-flex gap-2 align-items-center">
                        <input type="checkbox" class="form-check-input" ${module.completed ? 'checked' : ''} 
                               onchange="toggleModule(${module.id}, this.checked)">
                        <button type="button" class="btn btn-sm btn-danger-custom" onclick="deleteModule(${module.id})">✕</button>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
}

async function createModule(event) {
    event.preventDefault();
    
    if (!selectedPlanId) {
        showToast('Please select a plan first', 'warning');
        return;
    }
    
    const data = {
        study_plan_id: selectedPlanId,
        title: document.getElementById('moduleTitle').value,
        description: document.getElementById('moduleDescription').value,
        order: document.getElementById('moduleOrder').value,
        duration: document.getElementById('moduleDuration').value
    };
    
    const response = await API.post('modules.php?action=create', data);
    
    if (response && response.success) {
        showToast(t('success-msg'), 'success');
        document.getElementById('createModuleForm').reset();
        loadModules();
        loadPlans();
    } else {
        showToast(t('error-msg'), 'danger');
    }
}

async function toggleModule(moduleId, completed) {
    const response = await API.post(`modules.php?action=update&id=${moduleId}`, { completed });
    if (response && response.success) {
        loadModules();
        loadPlans();
    }
}

async function deleteModule(moduleId) {
    if (confirmDelete('this module')) {
        const response = await API.delete(`modules.php?action=delete&id=${moduleId}`);
        if (response && response.success) {
            showToast(t('success-msg'), 'success');
            loadModules();
            loadPlans();
        } else {
            showToast(t('error-msg'), 'danger');
        }
    }
}

// ========== STUDY SESSIONS ==========
async function createSession(event) {
    event.preventDefault();
    
    const data = {
        study_plan_id: document.getElementById('sessionPlan').value,
        date: document.getElementById('sessionDate').value,
        duration: document.getElementById('sessionDuration').value,
        notes: document.getElementById('sessionNotes').value
    };
    
    const response = await API.post('sessions.php?action=create', data);
    
    if (response && response.success) {
        showToast(t('success-msg'), 'success');
        document.getElementById('createSessionForm').reset();
        loadSessions();
        updateDashboard();
    } else {
        showToast(t('error-msg'), 'danger');
    }
}

async function loadSessions() {
    if (allPlans.length === 0) {
        document.getElementById('sessionsList').innerHTML = `
            <div class="empty-state">
                <div class="empty-state-icon">📝</div>
                <div class="empty-state-title" data-i18n="no-sessions">No study sessions</div>
            </div>
        `;
        return;
    }
    
    let allSessions = [];
    
    for (let plan of allPlans) {
        const response = await API.get(`sessions.php?action=today&plan_id=${plan.id}`);
        if (response && Array.isArray(response)) {
            allSessions.push(...response.map(s => ({
                ...s,
                plan_name: plan.name
            })));
        }
    }
    
    renderSessions(allSessions);
}

function renderSessions(sessions) {
    const container = document.getElementById('sessionsList');
    
    if (!sessions || sessions.length === 0) {
        container.innerHTML = `
            <div class="empty-state">
                <div class="empty-state-icon">📝</div>
                <div class="empty-state-title" data-i18n="no-sessions">No study sessions</div>
            </div>
        `;
        return;
    }
    
    container.innerHTML = sessions.map(session => `
        <div class="card card-custom">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="card-title">${session.plan_name}</h6>
                        <small class="text-muted">${session.session_date} • ${session.duration} ${t('minutes')}</small>
                        ${session.notes ? `<p class="card-text small mt-2">${session.notes}</p>` : ''}
                    </div>
                    <button type="button" class="btn btn-sm btn-danger-custom" onclick="deleteSession(${session.id})">✕</button>
                </div>
            </div>
        </div>
    `).join('');
}

async function deleteSession(sessionId) {
    if (confirmDelete('this session')) {
        const response = await API.delete(`sessions.php?action=delete&id=${sessionId}`);
        if (response && response.success) {
            showToast(t('success-msg'), 'success');
            loadSessions();
            updateDashboard();
        } else {
            showToast(t('error-msg'), 'danger');
        }
    }
}

// ========== DAILY GOALS ==========
async function createGoal(event) {
    event.preventDefault();
    
    const data = {
        study_plan_id: document.getElementById('goalPlan').value,
        date: document.getElementById('goalDate').value,
        target_duration: document.getElementById('targetDuration').value
    };
    
    const response = await API.post('goals.php?action=create', data);
    
    if (response && response.success) {
        showToast(t('success-msg'), 'success');
        document.getElementById('createGoalForm').reset();
        loadGoals();
        updateDashboard();
    } else {
        showToast(t('error-msg'), 'danger');
    }
}

async function loadGoals() {
    if (allPlans.length === 0) {
        document.getElementById('goalsList').innerHTML = `
            <div class="empty-state">
                <div class="empty-state-icon">🎯</div>
                <div class="empty-state-title" data-i18n="no-goals">No goals set</div>
            </div>
        `;
        return;
    }
    
    const today = new Date().toISOString().split('T')[0];
    let allGoals = [];
    
    for (let plan of allPlans) {
        const response = await API.get(`goals.php?action=today&plan_id=${plan.id}&date=${today}`);
        if (response) {
            allGoals.push({
                ...response,
                plan_name: plan.name
            });
        }
    }
    
    renderGoals(allGoals);
}

function renderGoals(goals) {
    const container = document.getElementById('goalsList');
    
    if (!goals || goals.length === 0) {
        container.innerHTML = `
            <div class="empty-state">
                <div class="empty-state-icon">🎯</div>
                <div class="empty-state-title" data-i18n="no-goals">No goals set</div>
            </div>
        `;
        return;
    }
    
    container.innerHTML = goals.map(goal => `
        <div class="card card-custom">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="card-title mb-0">${goal.plan_name}</h6>
                    ${goal.achieved ? '<span class="badge badge-green" data-i18n="completed">Completed</span>' : ''}
                </div>
                <small class="text-muted">${goal.target_duration} ${t('minutes')} target • ${goal.actual_duration} ${t('minutes')} done</small>
                <div class="progress-custom mt-2">
                    <div class="progress-bar-custom" style="width: ${Math.min((goal.actual_duration / goal.target_duration) * 100, 100)}%"></div>
                </div>
            </div>
        </div>
    `).join('');
}

// ========== DASHBOARD ==========
async function loadDashboard() {
    await loadPlans();
    loadModules();
    loadSessions();
    loadGoals();
    updateDashboard();
}

async function updateDashboard() {
    const today = new Date().toISOString().split('T')[0];
    
    // Total plans
    document.getElementById('totalPlans').textContent = allPlans.length;
    
    // Study time today
    let totalStudyTimeToday = 0;
    for (let plan of allPlans) {
        const response = await API.get(`sessions.php?action=today&plan_id=${plan.id}&date=${today}`);
        if (response && Array.isArray(response)) {
            totalStudyTimeToday += response.reduce((sum, s) => sum + parseInt(s.duration), 0);
        }
    }
    document.getElementById('studyTimeToday').textContent = totalStudyTimeToday;
    
    // Goals achieved today
    let goalsAchievedCount = 0;
    for (let plan of allPlans) {
        const response = await API.get(`goals.php?action=today&plan_id=${plan.id}&date=${today}`);
        if (response && response.achieved) {
            goalsAchievedCount++;
        }
    }
    document.getElementById('goalsAchieved').textContent = goalsAchievedCount;
    
    // Average progress
    if (allPlans.length > 0) {
        const avgProgress = Math.round(
            allPlans.reduce((sum, p) => sum + p.progress, 0) / allPlans.length
        );
        document.getElementById('avgProgress').textContent = avgProgress + '%';
    }
    
    renderDashboardPlans();
}

function renderDashboardPlans() {
    const container = document.getElementById('dashboardPlans');
    
    if (allPlans.length === 0) {
        container.innerHTML = `
            <div class="empty-state">
                <div class="empty-state-icon">📚</div>
                <div class="empty-state-title">Create your first learning plan to get started!</div>
            </div>
        `;
        return;
    }
    
    container.innerHTML = allPlans.map(plan => `
        <div class="card card-custom mb-3">
            <div class="card-body">
                <h6 class="card-title mb-2">${plan.name}</h6>
                <div class="progress-custom mb-2">
                    <div class="progress-bar-custom" style="width: ${plan.progress}%"></div>
                </div>
                <small class="text-muted">${plan.progress}% • ${plan.modules_count} Modules • ${plan.total_duration} ${t('minutes')} total</small>
            </div>
        </div>
    `).join('');
}

// ========== UTILITIES ==========
function updatePlanSelectors() {
    const sessionPlanSelect = document.getElementById('sessionPlan');
    const goalPlanSelect = document.getElementById('goalPlan');
    const planSelectorDiv = document.getElementById('planSelector');
    
    const optionsHTML = allPlans.map(plan => 
        `<option value="${plan.id}">${plan.name}</option>`
    ).join('');
    
    sessionPlanSelect.innerHTML = optionsHTML;
    goalPlanSelect.innerHTML = optionsHTML;
    
    renderPlanSelector();
}

function populatePlanSelectors() {
    loadPlans();
}
