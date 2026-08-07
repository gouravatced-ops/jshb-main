<?php
$app = \App\Models\Application::find(8);
if ($app) {
    $app->status = 'in_progress';
    $app->current_role_id = 4;
    $app->current_user_id = 18;
    $app->current_step_id = 14;
    $app->save();

    $movement = \App\Models\ApplicationMovement::where('application_id', 8)->latest()->first();
    if ($movement && $movement->action_type == 'approved') {
        $movement->delete();
    }
    echo 'Reverted';
}
