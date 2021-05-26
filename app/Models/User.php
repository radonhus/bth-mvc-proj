<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
* The following properties are columns in the table that
* the model represents (to make phpstan happy)
*
* @property int $id
* @property string $name
* @property int $coins
* @method get()
* @method where(mixed $columnOrArray = '', mixed $columnValue = '')
* @method orderByDesc(string $column)
* @method create(array $request)
*/
class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     *  Encrypt password before storing in database.
     * @param string $password
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get all users
     *
     * @property array  $usersData
     * @property array  $users
     * @return array  $users
     */
    public function getAllUsers(): array
    {

        $usersData = $this->get();

        $users = [];

        foreach ($usersData as $user) {
            array_push($users, [
                'id' => $user->id,
                'name' => $user->name,
                'coins' => $user->coins
            ]);
        }

        return $users;
    }

    /**
     * Get top 10 richest users
     *
     * @property array  $usersData
     * @property int  $richest
     * @property array  $users
     * @property int  $percent
     * @return array  $users
     */
    public function getRichestUsers(): array
    {

        $usersData = $this->orderByDesc('coins')
                                ->limit(10)
                                ->get();

        $richest = intval($usersData[0]['coins']);

        $users = [];

        foreach ($usersData as $user) {
            $percent = round((intval($user->coins) / $richest) * 100);
            array_push($users, [
                'id' => $user->id,
                'name' => $user->name,
                'coins' => $user->coins,
                'percent' => $percent
            ]);
        }

        return $users;
    }

    /**
     * Get amount of coins for one user
     *
     * @param int $userId
     * @property array $user
     * @return string $coins
     */
    public function getCoins(int $userId): string
    {

        $user = $this->where('id', $userId)
                                ->get();

        $coins = $user[0]->coins;

        return $coins;
    }

    /**
     * Get name for one user
     *
     * @param int $userId
     * @property array $user
     * @return string $name
     */
    public function getName(int $userId): string
    {

        $user = $this->where('id', $userId)
                                ->get();

        $name = $user[0]->name;

        return $name;
    }

    /**
     * Update coins for one user
     *
     * @param int $userId
     * @param int $amount
     * @property int $currentBalance
     * @property int $newBalance
     * @property string $updatedRows
     * @property array $user
     * @return string $updatedRows
     */
    public function updateBalance(int $userId, int $amount): string
    {
        $user = $this->where('id', $userId)
                                ->get();

        $currentBalance = intval($user[0]->coins);

        $newBalance = $currentBalance + $amount;

        $updatedRows = $this->where('id', $userId)
                                ->update(['coins' => $newBalance]);

        return $updatedRows;
    }

    /**
     * Check if username is already taken
     *
     * @param string  $username
     * @property array  $usernames
     * @return bool
     */
    public function checkUserNameTaken(string $username): bool
    {

        $usernames = $this->where('name', $username)
                                ->limit(1)
                                ->get();

        return count($usernames) > 0;
    }
}
