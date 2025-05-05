<div>
<div class="container mt-5">
<div class="row">
    <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
        <div class="login-brand">
            <img src="/assets/img/stisla-fill.svg" alt="logo" width="100" class="shadow-light rounded-circle">
        </div>
        <div class="card card-primary">
            <div class="card-header"><h4>Login</h4></div>
                <div class="card-body">
                    <form wire:submit="login" class="needs-validation" novalidate="">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input id="email" type="email" class="form-control" wire:model="email" tabindex="1" required autofocus>
                        </div>

                        <div class="form-group">
                        <div class="d-block">
                            <label for="password" class="control-label">Password</label>
                        </div>
                        <input id="password" type="password" class="form-control" wire:model="password" tabindex="2" required>
                        </div>

                        <div class="form-group">
                        <button wire:loading.remove wire:target="login" type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                            Login
                        </button>
                        <button wire:loading wire:target="login" class="btn btn-primary btn-lg btn-block" tabindex="4">
                            Loading ...
                        </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>    
</div>
</div>
