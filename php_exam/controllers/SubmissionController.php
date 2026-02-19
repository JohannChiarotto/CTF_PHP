<?php
// controllers/SubmissionController.php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Middleware.php';
require_once __DIR__ . '/../core/Security.php';
require_once __DIR__ . '/../models/Challenge.php';
require_once __DIR__ . '/../models/Submission.php';

class SubmissionController extends Controller
{
    public function submit(): void
    {
        Middleware::requireAuth();
        if (!$this->isPost() || !Security::validateCsrfToken($_POST['csrf_token'] ?? null)) {
            $this->redirect('/');
        }

        $challengeId = (int) ($_POST['challenge_id'] ?? 0);
        $flag        = trim($_POST['flag'] ?? '');

        if ($challengeId <= 0 || $flag === '') {
            $this->redirect('/');
        }

        $challenge = Challenge::find($challengeId);
        if (!$challenge) {
            $this->redirect('/');
        }

        $success = Submission::submitFlag(Auth::id(), $challengeId, $flag, $challenge['flag_hash']);

        $this->view('home/detail', [
            'challenge'   => $challenge,
            'flagResult'  => $success ? 'Flag correct !' : 'Flag incorrect',
            'currentPage' => 'home',
        ]);
    }
}

