<?php
header('Content-Type: application/json');
require_once '../config.php';

$method = $_SERVER['REQUEST_METHOD'];
$action = isset($_GET['action']) ? $_GET['action'] : '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$plan_id = isset($_GET['plan_id']) ? (int)$_GET['plan_id'] : 0;
$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

if ($method === 'GET') {
    if ($action === 'list' && $plan_id) {
        // Get all sessions for a plan
        $stmt = executeQuery(
            "SELECT * FROM study_sessions WHERE study_plan_id = ? ORDER BY session_date DESC LIMIT 20",
            [$plan_id]
        );
        echo json_encode(getResults($stmt));
    } elseif ($action === 'today' && $plan_id) {
        // Get today's sessions for a plan
        $stmt = executeQuery(
            "SELECT * FROM study_sessions WHERE study_plan_id = ? AND session_date = ? ORDER BY created_at DESC",
            [$plan_id, $date]
        );
        echo json_encode(getResults($stmt));
    }
} elseif ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    
    if ($action === 'create') {
        $session_date = $data['date'] ?? date('Y-m-d');
        $duration = (int)$data['duration'];
        
        $stmt = executeQuery(
            "INSERT INTO study_sessions (study_plan_id, session_date, duration, notes) VALUES (?, ?, ?, ?)",
            [$data['study_plan_id'], $session_date, $duration, $data['notes'] ?? '']
        );
        
        if (getAffectedRows() > 0) {
            $session_id = getLastInsertId();
            
            // Update daily goal
            $stmt = executeQuery(
                "SELECT * FROM daily_goals WHERE study_plan_id = ? AND goal_date = ?",
                [$data['study_plan_id'], $session_date]
            );
            $goal = getRow($stmt);
            
            if ($goal) {
                $new_duration = $goal['actual_duration'] + $duration;
                $achieved = ($new_duration >= $goal['target_duration']) ? 1 : 0;
                
                $stmt = executeQuery(
                    "UPDATE daily_goals SET actual_duration = ?, achieved = ? WHERE id = ?",
                    [$new_duration, $achieved, $goal['id']]
                );
            }
            
            echo json_encode(['success' => true, 'id' => $session_id]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to create session']);
        }
    }
} elseif ($method === 'DELETE') {
    if ($id) {
        $stmt = executeQuery("DELETE FROM study_sessions WHERE id = ?", [$id]);
        
        if (getAffectedRows() > 0) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to delete session']);
        }
    }
}
?>
