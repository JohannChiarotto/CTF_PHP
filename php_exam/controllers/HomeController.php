<?php
// controllers/HomeController.php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/Challenge.php';

class HomeController extends Controller
{
    public function index(): void
    {
        $challenges = Challenge::all();
        
        // Vérifier pour chaque challenge si l'utilisateur l'a acheté
        $challengesWithStatus = [];
        if (Auth::check()) {
            $pdo = getPDO();
            foreach ($challenges as $c) {
                $stmt = $pdo->prepare('SELECT COUNT(*) FROM `UserChallenge` WHERE user_id = ? AND challenge_id = ?');
                $stmt->execute([Auth::id(), $c['id']]);
                $c['userHasBought'] = (int) $stmt->fetchColumn() > 0;
                $challengesWithStatus[] = $c;
            }
        } else {
            foreach ($challenges as $c) {
                $c['userHasBought'] = false;
                $challengesWithStatus[] = $c;
            }
        }
        
        $this->view('home/index', [
            'challenges'  => $challengesWithStatus,
            'currentPage' => 'home',
        ]);
    }

    public function detail(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            $this->redirect('/');
        }

        $challenge = Challenge::find($id);
        if (!$challenge) {
            $this->redirect('/');
        }
        
        // Vérifier si l'utilisateur a acheté ce challenge
        $challenge['userHasBought'] = false;
        if (Auth::check()) {
            $pdo = getPDO();
            $stmt = $pdo->prepare('SELECT COUNT(*) FROM `UserChallenge` WHERE user_id = ? AND challenge_id = ?');
            $stmt->execute([Auth::id(), $id]);
            $challenge['userHasBought'] = (int) $stmt->fetchColumn() > 0;
        }

        $this->view('home/detail', [
            'challenge'   => $challenge,
            'currentPage' => 'home',
        ]);
    }
}

