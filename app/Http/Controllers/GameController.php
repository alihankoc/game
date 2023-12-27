<?php

namespace App\Http\Controllers;

use App\Http\Requests\EndGameRequest;
use App\Http\Requests\StartGameRequest;
use App\Models\Game;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class GameController extends Controller
{

    public function startGame (StartGameRequest $request) {
        $data = $request->validated();

        $user = User::updateOrCreate([
            'email' => $data['email'],
        ], [
            'name' => $data['name'],
            'surname' => $data['surname'],
        ]);

        /*
         * Questions:
         * 1- Should we check if there is a not ended game
         * 2- Should we check if game started today, in last 24 hours, etc.
         */
        $game = Game::create([
            'user_id' => $user->id,
            'is_ended' => false
        ]);

        return response()->json([
            'message' => 'Game started!',
            'data' => $game
        ], Response::HTTP_CREATED);

    }

    public function endGame (EndGameRequest $request) {
        $data = $request->validated();

        $game = Game::where([
            'user_id' => $data['user_id'],
            'id' => $data['game_id'],
            'is_ended' => false,
        ])->first();

        if (!$game) {
            abort(404, 'Game not found!');
        }

        $game->update(['score' => $data['score'], 'is_ended' => true]);

        $bestScoreToday = Game::where('user_id', $data['user_id'])
            ->whereDate('updated_at', now()->toDateString())
            ->where('is_ended', true)
            ->max('score');


        $userPositionToday = Game::whereDate('created_at', now()->toDateString())
            ->where('score', '>', $bestScoreToday)
            ->groupBy('user_id')
            ->select('user_id', DB::raw('MAX(score) as best_score'))
            ->havingRaw('MAX(score) > ?', [$bestScoreToday])
            ->count() + 1;


        return response()->json([
            'message' => 'Game ended!',
            'data' => [
                'best_score_today' => $bestScoreToday,
                'position' => $userPositionToday
            ]
        ], Response::HTTP_OK);

    }

    public function showLeaderboard () {
        $todaysLeaderBoard = Game::with(['user:id,name,surname'])
            ->select('user_id', DB::raw('MAX(score) as best_score'))
            ->whereDate('created_at', now()->toDateString())
            ->groupBy('user_id')
            ->orderByDesc('best_score')
            ->limit(10)
            ->get('user_id');

        return response()->json([
            'message' => 'Leaderboard fetched!',
            'data' => $todaysLeaderBoard
        ], Response::HTTP_OK);
    }
}
