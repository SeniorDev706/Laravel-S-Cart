@extends($sc_templatePath.'.layout')

@section('block_main')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ sc_language_render('customer.verify_email.title_header') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ sc_language_render('customer.verify_email.msg_sent') }}
                        </div>
                    @endif

                    {{ sc_language_render('customer.verify_email.msg_page_1') }}
                    <form class="d-inline" method="POST" action="">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ sc_language_render('customer.verify_email.msg_page_2') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
