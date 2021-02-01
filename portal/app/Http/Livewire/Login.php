<?php

namespace App\Http\Livewire;

use App\Models\players;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use function App\Helpers\passwd_compat_hasher;

class Login extends Component
{
    public $game = '';
    public $username = '';
    public $password = '';
    public $honeyPasses = '';
    public $honeyInputs = '';

    public function render()
    {
        return view('livewire.Login');
    }

    protected $rules = [
        'game' => 'required',
        'username' => 'bail|required|min:2|max:12',
        'password' => 'required|min:4|max:20',
    ];

    private function resetInputFields()
    {
        $this->password = '';
        $this->honeyPasses = '';
        $this->honeyInputs = '';
    }

    public function updated($field)
    {
        try {
            $this->validateOnly($field);
        } catch (ValidationException $e) {
        }
    }

    public function authenticate()
    {
        $this->validate([
            'game' => 'required',
            'username' => ['bail', 'required', 'min:2', 'max:12', 'exists:' . $this->game . '.players'],
            'password' => ['required', 'min:2', 'max:20'],
        ]);

        $trimmed_username = trim(preg_replace('/[-_.]/', ' ', $this->username));
        $user = players::on($this->game)->where('username', $trimmed_username)->first();

        if ($user->salt) {
            // accounts with old password compatibility
            $trimmed_pass = passwd_compat_hasher(trim($this->password), $user->salt);
        } else {
            $trimmed_pass = trim($this->password);
        }

        $credentials = [
            'username' => $trimmed_username,
            'pass' => $trimmed_pass,
        ];

        if (!$user || !Hash::check($trimmed_pass, $user->pass)) {
            throw ValidationException::withMessages([
                'password' => ['The provided credentials are incorrect'],
            ]);
        }

        //return $user->createToken($this->device_name)->plainTextToken;

        $this->resetInputFields();
        session()->flash('success', 'Success');
        //session()->regenerate();
        //return redirect()->route('Home');
    }

    public function logout()
    {
        Auth::logout();
        return redirect(route('Secure_Login'));
    }
}