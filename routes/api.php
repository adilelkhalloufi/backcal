<?php

use App\Mail\WelecomeEmail;
use App\Models\Calendrier;
use App\Models\EventCal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', function (Request $request) {
    try {
        $user = User::where('email', '=', $request->email)->first();
        if (!$user) {
            return response()->json(
                ['msg' => 'mail n\'existe pas'],
                201,
                ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        }

        if (Hash::check($request->password, $user->password)) {
        } else {
            return response()->json(
                ['msg' => 'Le mot de passe ne correspond pas'],
                201,
                ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        }


        return response()->json(
            $user,
            200,
            ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
            JSON_UNESCAPED_UNICODE
        );
    } catch (\Throwable $th) {
        return response()->json(
            ["Message" => "Faild"],
            201,
            ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
            JSON_UNESCAPED_UNICODE
        );
    }
});
Route::post('user_create', function (Request $request) {

    try {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password, [
                'rounds' => 12,
            ]),
        ]);
        $details = [
            'title' => 'bienvenue with adevCal',
            'body' => 'bienvenue'
        ];
        Mail::to($request->email)->send(new WelecomeEmail($details));
        return response()->json(
            $user,
            200,
            ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
            JSON_UNESCAPED_UNICODE
        );
    } catch (\Throwable $th) {
        return response()->json(
            ['msg' => 'Email already Existe'],
            201,
            ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
            JSON_UNESCAPED_UNICODE
        );
    }
});

Route::get('email/{id}', function ($id) {
    try {
        $user = User::findOrFail($id);
        $details = [
            'title' => 'Welecom with adevCal',
            'body' => 'This is content emails AdevCal'
        ];
        Mail::to($user->email)->send(new WelecomeEmail($details));
        return ["Message" => "Mail is Send"];
    } catch (\Throwable $th) {
        return ["Message" => "Email Faild"];
    }
});

Route::post('user_update/{id}', function (Request $request, $id) {
    try {
        $user = User::where('id', '=', $id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password, [
                'rounds' => 12,
            ]),
        ]);
        return [
            "Message" => "Mail is Send",
            "NewUser" => $user
        ];
    } catch (\Throwable $th) {
        return ["Message" => "Probleme Happend"];
    }
});
Route::post('resete', function (Request $request) {
    try {
        $newp = Str::random(10);

        $user = User::where('email', '=', $request->email)->update(array(
            'password' =>  Hash::make($newp),
        ));

        $details = [
            'title' => 'Reset Password Email',
            'body' => "This new Password " . $newp
        ];
        Mail::to($request->email)->send(new WelecomeEmail($details));

        return ["Message" => "Mail is Send"];
    } catch (\Throwable $th) {
        return ["Message" => "Email Faild"];
    }
});

Route::post('create_cal', function (Request $request) {
    try {
        $resultat =  Calendrier::create($request->all());
        return ["Message" => "Succes", "resultat" => $resultat];
    } catch (\Throwable $th) {
        return response()->json(
            ['msg' => 'Problem Serveur'],
            201,
            ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
            JSON_UNESCAPED_UNICODE
        );
    }
});
Route::post('update_cal/{id}', function (Request $request, $id) {
    try {
        $cam = Calendrier::where("id", '=', $id)->update($request->all());
        return ["Message" => "Succes", "resultat" => $cam];
    } catch (\Throwable $th) {
        return response()->json(
            ['msg' => 'Le mot de passe ne correspond pas'],
            201,
            ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
            JSON_UNESCAPED_UNICODE
        );
    }
});

Route::post('create_evn', function (Request $request) {

    try {
        $resultat =  EventCal::create($request->all());
        return ["Message" => "Succes", "resultat" => $resultat];
    } catch (\Throwable $th) {
        return response()->json(
            ['msg' => 'Probleme Serveur'],
            201,
            ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
            JSON_UNESCAPED_UNICODE
        );
    }
});
Route::post('update_evn', function (Request $request, $id) {
    try {
        $cam = Event::where("id", '=', $id)->update($request->all());
        return ["Message" => "Succes", "resultat" => $cam];
    } catch (\Throwable $th) {
        return response()->json(
            ['msg' => 'Le mot de passe ne correspond pas'],
            201,
            ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
            JSON_UNESCAPED_UNICODE
        );
    }
});


Route::post('list_evn', function (Request $request) {
    try {
        $ev = EventCal::where("user_id", '=', $request->user_id)->get();
        return  response()->json(
            $ev,
            200,
            ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
            JSON_UNESCAPED_UNICODE
        );
    } catch (\Throwable $th) {
        return response()->json(
            ['msg' => 'Le mot de passe ne correspond pas'],
            201,
            ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
            JSON_UNESCAPED_UNICODE
        );
    }
});
Route::post('calandrai_evn', function (Request $request) {
    try {
        $ev = Calendrier::where("user_id", '=', $request->user_id)->get();
        return  response()->json(
            $ev,
            200,
            ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
            JSON_UNESCAPED_UNICODE
        );
    } catch (\Throwable $th) {
        return response()->json(
            ['msg' => 'Le mot de passe ne correspond pas'],
            201,
            ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
            JSON_UNESCAPED_UNICODE
        );
    }
});
Route::post('count', function (Request $request) {
    try {
        $date = Carbon::createFromFormat('Y-m-d', $request->date);
        $users =  DB::table('events')
            ->select("events.cal_id", DB::raw("count(events.id) as CountEvents"))
            ->whereMonth('events.date_debut', '=',  $date->format('m'))
            ->whereYear('events.date_debut', '=',  $date->format('Y'))
            ->groupBy('events.cal_id')->get();
        return  response()->json(
            $users,
            200,
            ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
            JSON_UNESCAPED_UNICODE
        );
    } catch (\Throwable $th) {
        return response()->json(
            ['msg' => 'Le mot de passe ne correspond pas'],
            201,
            ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
            JSON_UNESCAPED_UNICODE
        );
    }
});
