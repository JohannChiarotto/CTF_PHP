<?php
// controllers/ScoreboardController.php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Scoreboard.php';

class ScoreboardController extends Controller
{
    public function index(): void
    {
        $top = Scoreboard::top();
        $this->view('scoreboard/index', [
            'top'         => $top,
            'currentPage' => 'scoreboard',
            'pageStyles'  => ['/instance/css/scoreboard.css'],
        ]);
    }
}

