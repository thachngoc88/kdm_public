<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard as Auth;
use Illuminate\Support\Facades\App;

class CheckPermissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
	protected $auth;
	protected $permissions = [];
    protected $except = [
        '/',
        'login',
        'logout',
        'register'
    ];
	/**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
	public function __construct(Auth $auth)
	{
		$this->auth = $auth;
	}
    public function handle($request, Closure $next)
    {
        if ($this->inExceptArray($request, $this->except)
            || ($this->auth->check() && $this->checkAccess($request)))
        {
            return $next($request);

        }else {
            //return redirect()->route('403');
            abort(403, 'Unauthorized action.');
        }
    }
	public function checkAccess($request) {
        $this->getPermissionsRoles();
        if ($this->inExceptArray($request, $this->permissions)) {
            return true;
        }
        return false;
    }
    private function getPermissionsRoles () {
        $roler = App::make('roler');
        if ($roler->isChallengeUser())
            $this->permissions = [
                'recordinput/*',
                'mapsheet/*',
                'unit/*',
                'pdfdownload/q/*',
                'pdfdownload/a/*',
                'home'


            ];
        elseif($roler->isPrefectureUser())
        {
            $this->permissions = [
                'home',
                'home/admin',
                'setting*',
                'messages*',
                'classfiltering/*',
                'home/challenge',
                'users',
                'users/*',
                'aggregation',
                'aggregation/*',
                'individual',
                'individual/*',
                'classfiltering/*',
                'pdfdownload/q/*',
                'pdfdownload/a/*',
                'mapsheet/*',
                'unit/*',
                'backupdownload/',
                'backupdownload/*',
                'manualdownload/',
                'manualdownload/*',
            ];
        }
        else {
            $this->permissions = [
                'home',
                'home/admin',
                'messages*',
                'classfiltering/*',
                'users',
                'users/*',
                'aggregation',
                'aggregation/*',
                'individual',
                'individual/*',
                'classfiltering/*',
                'home/challenge',
                'pdfdownload/q/*',
                'pdfdownload/a/*',
                'mapsheet/*',
                'unit/*',
                'manualdownload/',
                'manualdownload/*',
            ];
        }

    }
    protected function inExceptArray($request, array $exceptArray = [])
    {
        foreach ($exceptArray as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }
            if ($request->is($except)) {
                return true;
            }
        }
        return false;
    }
}
