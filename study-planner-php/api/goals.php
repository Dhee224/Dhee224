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
        // Get all goals for a plan
        $stmt = executeQuery(
            "SELECT * FROM daily_goals WHERE study_plan_id = ? ORDER BY goal_date DESC LIMIT 30",
            [$plan_id]
        );
        echo json_encode(getResults($stmt));
    } elseif ($action === 'today' && $plan_id) {
        // Get today's goal
        $stmt = executeQuery(
            "SELECT * FROM daily_goals WHERE study_plan_id = ? AND goal_date = ?",
            [$plan_id, $date]
        );
        $goal = getRow($stmt);
        echo json_encode($goal);
    }
} elseif ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    
    if ($action === 'create') {
        $goal_date = $data['date'] ?? date('Y-m-d');
        
        // Check if goal already exists
        $stmt = executeQuery(
            "SELECT * FROM daily_goals WHERE study_plan_id = ? AND goal_date = ?",
            [$data['study_plan_id'], $goal_date]
        );
        $existing = getRow($stmt);
        
        if ($existing) {
            // Update existing goal
            $stmt = executeQuery(
                "UPDATE daily_goals SET target_duration = ? WHERE id = ?",
                [$data['target_duration'], $existing['id']]
            );
            $goal_id = $existing['id'];
        } else {
            // Create new goal
            $stmt = executeQuery(
                "INSERT INTO daily_goals (study_plan_id, goal_date, target_duration) VALUES (?, ?, ?)",
                [$data['study_plan_id'], $goal_date, $data['target_duration']]
            );
            $goal_id = getLastInsertId();
        }
        
        if (getAffectedRows() > 0 || $existing) {
            echo json_encode(['success' => true, 'id' => $goal_id]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to create goal']);
        }
    } elseif ($action === 'update' && $id) {
        $stmt = executeQuery(
            "UPDATE daily_goals SET actual_duration = ?, achieved = ? WHERE id = ?",
            [$data['actual_duration'] ?? 0, $data['achieved'] ?? 0, $id]
        );
        
        echo json_encode(['success' => getAffectedRows() > 0]);
    }
} elseif ($method === 'DELETE') {
    if ($id) {
        $stmt = executeQuery("DELETE FROM daily_goals WHERE id = ?", [$id]);
        
        if (getAffectedRows() > 0) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to delete goal']);
        }
    }
}
?>
