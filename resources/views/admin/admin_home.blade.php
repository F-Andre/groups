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
                <th>Nom</th>
                <th>e-mail</th>
                <th>Nbre de posts</th>
                <th>Nbre de commentaires</th>
                <th>Admin?</th>
                <th>Date de création</th>
            </tr>
        </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td><a href="{{ route('admin.show', [$user]) }}">{{ $user->name }}</a></td>
                        <td>{{ $user->email }}</td>
                        <td class="text-center">{{ $user->userPosts()->count() }}</td>
                        <td class="text-center">{{ $user->userComments()->count() }}</td>
                        <td class="text-center">{{ $user->admin }}</td>
                        <td>{{ Date::parse($user->created_at)->format('d F Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
    </table>
    {{ $links }}
@endsection
