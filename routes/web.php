<?php


Route::get('logout', 'Auth\LoginController@logout')->name('logout');

Auth::routes(['register' => false]);

Route::get('/login', function () {
    Auth::logout();
    return view('auth.login');
})->name('login');
Route::get('/', 'LogBookController@home')->middleware(['auth', 'auth.unique.user', 'check.sec.user'])->name('home');

Route::group(['prefix' => '/admin', 'middleware' => ['auth', 'web', 'auth.unique.user', 'check.sec.user']], function () {

    Route::get('/users', 'UserController@mostra')->name('users');
    Route::get('/editprofile', 'UserController@editUser')->name('editConta');
    Route::post('/update', 'UserController@update')->name('updateAccount');
});
Route::group(['prefix' => '/employees', 'middleware' => ['auth', 'web', 'auth.unique.user', 'check.sec.user']], function () {

    Route::get('/', 'EmployeesController@show')->name('showEmployees');
    Route::get('/adicionar', 'EmployeesController@criaFunc')->name('criaFunc');
    Route::post('/create', 'EmployeesController@create')->name('createFunc');
    Route::get('/delete/{id}', 'EmployeesController@delete')->name('deleteFunc');
    Route::get('/auths/{id}', 'EmployeesController@authsFuncVehi')->name('authsFuncVehi');
    Route::post('/update', 'EmployeesController@update')->name('updateFunc');
    Route::get('/getdata', 'EmployeesController@getdata')->name('getdataFunc');
    Route::get('/profileFunc', 'EmployeesController@profileFunc')->name('profileFunc');
    Route::post('/upProfileFunc', 'EmployeesController@upProfileFunc')->name('upProfileFunc');
    Route::get('/auths/{id}', 'EmployeesController@authsFuncVehi')->name('authsFuncVehi');
    Route::get('/searchfunc/{id}', 'EmployeesController@searchfunc')->name('searchfunc');
    Route::get('/allfuncs/secretaria/{id}', 'EmployeesController@funcsSec');
});

Route::group(['prefix' => '/setores', 'middleware' => ['auth', 'web', 'auth.unique.user', 'check.sec.user']], function () {

    Route::get('/', 'SetoresController@show')->name('setores');
    Route::get('/Adicionar', 'SetoresController@adcSetor')->name('adcSetor');
    Route::post('/create', 'SetoresController@create')->name('createSector');
    Route::get('/pesquisaSector/{id}', 'SetoresController@sectors')->name('sector');
    Route::get('/delete/{id}', 'SetoresController@delete')->name('deleteSector');
    Route::get('/getdata', 'SetoresController@getdata')->name('getdataSectors');
    Route::get('/getsectors/{id}', 'SetoresController@getsectors')->name('getsectors');
});
Route::group(['prefix' => '/secretarias', 'middleware' => ['auth', 'web', 'auth.unique.user', 'check.sec.user']], function () {

    Route::get('/', 'SecretariasController@show')->name('SecretariasShow');
    Route::get('/add', 'SecretariasController@adicionar')->name('adicionarSec');
    Route::post('/create', 'SecretariasController@create')->name('createSec');
    Route::get('/view/{id}', 'SecretariasController@view')->name('viewSec');
    Route::get('/getdata', 'SecretariasController@getdata')->name('getdataSecs');
    Route::get('/getdataadms/{id}', 'SecretariasController@getdataAdms')->name('getdataAdms');
    Route::post('/changerole', 'SecretariasController@changerole')->name('changerole');
});
Route::group(['prefix' => '/veiculos', 'middleware' => ['auth', 'web', 'auth.unique.user', 'check.sec.user']], function () {

    Route::get('/', 'VeiculosController@show')->name('VeiculosShow');
    Route::get('/anexar', 'VeiculosController@anexarVeiculo')->name('anexarVeiculo');
    Route::post('/create', 'VeiculosController@create')->name('createVeiculo');
    Route::post('/update', 'VeiculosController@update')->name('updatevehi');
    Route::get('/delete/{id}', 'VeiculosController@delete')->name('deleteVei');
    Route::get('/allVehicles/{id}', 'VeiculosController@allVehicles')->name('allVehicles');
    Route::get('/getdata', 'VeiculosController@getdata')->name('getdataVehicles');
    Route::get('/view/{id}', 'VeiculosController@view')->name('viewVehi');
    Route::get('/authfuncs/{idVehi}', 'VeiculosController@getdataAuthsFunc')->name('getdataAuthsFunc');
    Route::post('/auth', 'VeiculosController@auth')->name('authVehicle');
    Route::post('/disallowance', 'VeiculosController@disallowance')->name('disallowanceVehicle');
    Route::get('/veiculo/{id}', 'VeiculosController@veiculo')->name('veiculo');
    Route::get('/lancamentogastos/{id}', 'VeiculosController@lancamentogastos')->name('lancamentogastos');
    Route::post('/lancargastos', 'VeiculosController@lancargastos')->name('lancargastos');
    Route::get('/gastos/{id}', 'VeiculosController@gastos')->name('gastos');
    Route::get('/delete/gastos/{id}', 'VeiculosController@delgastos')->name('delgastos');
    Route::get('/allvehicles/secretaria/{id}', 'VeiculosController@vehiclesSec');
    Route::get('/search/{id}', 'VeiculosController@search');
});
Route::group(['prefix' => '/logs', 'middleware' => ['auth', 'web', 'auth.unique.user', 'check.sec.user']], function () {

    Route::get('/', 'UsersActionsController@show')->name('UsersActionsShow');
    Route::get('/getdata', 'UsersActionsController@getdata')->name('getdataUsersActions');
});
Route::group(['prefix' => '/logbook', 'middleware' => ['auth', 'web', 'auth.unique.user', 'check.sec.user']], function () {

    Route::post('/create', 'LogBookController@create')->name('LogBookCreate');
    Route::post('/update', 'LogBookController@update')->name('voltarorigem');
    Route::post('/finalizar', 'LogBookController@finalizar')->name('finalizar');
});
Route::group(['prefix' => '/relatorios', 'middleware' => ['auth', 'web', 'auth.unique.user', 'check.sec.user']], function () {

    Route::get('/getdata', 'RelatoriosController@getdata')->name('getdataRelatorio');
    Route::get('/registroLogBook', 'RelatoriosController@registroLogBook')->name('registroLogBook');
    Route::post('/relatorioRequest', 'RelatoriosController@relatorioRequest')->name('relatorioRequest');
    Route::get('/viewDetails/{id}', 'RelatoriosController@viewDetails')->name('viewDetailsRelatorio');
    Route::get('/filtro', 'RelatoriosController@filtro')->name('filtroRela');
    Route::post('/gerador', 'RelatoriosController@gerador')->name('gerador');
});

Route::group(['prefix' => '/import', 'middleware' => ['auth', 'web', 'auth.unique.user', 'check.sec.user', 'update.solcit']], function () {

    Route::post('/veiculo', 'VeiculosController@import')->name('importExcelVeiculo');

});
Route::group(['prefix' => '/contato', 'middleware' => ['auth', 'web', 'auth.unique.user', 'check.sec.user']], function () {

    Route::get('/', 'ContatoController@contato')->name('contato');
    Route::post('/', 'ContatoController@contact')->name('createcontact');

});
Route::group(['prefix' => '/frota', 'middleware' => ['auth', 'web', 'auth.unique.user', 'check.sec.user']], function () {

    Route::get('/', 'FrotaController@show')->name('frota');
    Route::post('/searchvehicle', 'FrotaController@searchvehicle')->name('searchvehicle');
    Route::post('/searchdriver', 'FrotaController@searchdriver')->name('searchdriver');
    Route::post('/verifyauth', 'FrotaController@verifyauth')->name('verifyauth');

});

Route::post('/check', 'LogBookController@check');
Route::get('/teste/{car}/{func}/{local}', 'ContatoController@check');