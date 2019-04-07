@extends('admin/admin_template')

@section('section')
    @if (session()->has('ok'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        {{ session('ok') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
    {{ $links }}
    <table class="table table-striped table-inverse table-responsive">
        <thead class="thead-inverse">
            <tr>
                <th>@sortablelink('name', 'Nom')</th>
                <th>@sortablelink('email', 'E-mail')</th>
                <th>@sortablelink('postsQty', 'Nbre de posts')</th>
                <th>@sortablelink('comment.user', 'Nbre de commentaires')</th>
                <th>@sortablelink('admin', 'Admin')</th>
                <th>@sortablelink('created_at', 'Date de création')</th>
            </tr>
        </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td><a href="{{ route('admin.show', [$user]) }}">{{ $user->name }}</a></td>
                        <td>{{ $user->email }}</td>
                        <td class="text-center">{{ $user->postsQty }}</td>
                        <td class="text-center">{{ count($user->userComments()->get()) }}</td>
                        <td class="text-center">{{ $user->admin }}</td>
                        <td>{{ Date::parse($user->created_at)->format('d F Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
    </table>
    {{ $links }}
@endsection
