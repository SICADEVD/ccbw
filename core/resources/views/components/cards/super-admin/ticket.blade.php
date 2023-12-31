<div class="card ticket-message rounded-0 border-0  @if (user()->id == $user->id) bg-white-shade @endif" id="message-{{ $message->id }}">
    <div class="card-horizontal">
        <div class="card-img">
            <a href="{{ $user->is_superadmin ? 'javascript:;' : route('superadmin.companies.show', $user->cooperative_id) }}">
                <img class="" src="{{ $user->image_url }}" alt="{{ $user->name }}">
            </a>
        </div>
        <div class="card-body border-0 pl-0">
            <div class="d-flex">
                <a href="{{ $user->is_superadmin ? 'javascript:;' : (user()->is_superadmin ? route('superadmin.companies.show', $user->cooperative_id) : route('employees.show', $user->id)) }}">
                    <h4 class="card-title f-15 f-w-500 text-dark mr-3">{{ $user->name }}</h4>
                </a>
                <p class="card-date f-11 text-lightest mb-0">
                    {{ $message->created_at->timezone(global_setting()->timezone)->format(global_setting()->date_format . ' ' . global_setting()->time_format) }}
                </p>

                @if ($user->id == user()->id || user()->is_superadmin)
                    <div class="dropdown ml-auto message-action">
                        <button class="btn btn-lg f-14 p-0 text-lightest text-capitalize rounded  dropdown-toggle"
                            type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-ellipsis-h"></i>
                        </button>

                        <div class="dropdown-menu dropdown-menu-right border-grey rounded b-shadow-4 p-0"
                            aria-labelledby="dropdownMenuLink" tabindex="0">

                            <a class="dropdown-item delete-message"
                                data-row-id="{{ $message->id }}" data-user-id="{{ $user->id }}" href="javascript:;">@lang('app.delete')</a>
                        </div>
                    </div>
                @endif

            </div>
            @if ($message->message != '')
                <div class="card-text text-dark-grey text-justify mb-2">
                    <span class="ql-editor">{!! nl2br($message->message) !!}</span>
                </div>
            @endif

            {{ $slot }}

            <div class="d-flex flex-wrap">
                @foreach ($message->files as $file)
                    <x-file-card :fileName="$file->filename"
                        :dateAdded="$file->created_at->diffForHumans()">
                        @if ($file->icon == 'images')
                            <img src="{{ $file->file_url }}">
                        @else
                            <i class="fa {{ $file->icon }} text-lightest"></i>
                        @endif

                        <x-slot name="action">
                            <div class="dropdown ml-auto file-action">
                                <button
                                    class="btn btn-lg f-14 p-0 text-lightest text-capitalize rounded  dropdown-toggle"
                                    type="button" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    <i class="fa fa-ellipsis-h"></i>
                                </button>

                                <div class="dropdown-menu dropdown-menu-right border-grey rounded b-shadow-4 p-0"
                                    aria-labelledby="dropdownMenuLink" tabindex="0">
                                    @if ($file->icon != 'images')
                                        <a class="dropdown-item"
                                            target="_blank"
                                            href="{{ $file->file_url }}">@lang('app.view')</a>
                                    @endif

                                    <a class="dropdown-item"
                                        href="{{ route('superadmin.support-ticket-files.download', md5($file->id)) }}">@lang('app.download')</a>

                                    @if (user()->id == $user->id)
                                        <a class="dropdown-item delete-file"
                                            data-row-id="{{ $file->id }}"
                                            href="javascript:;">@lang('app.delete')</a>
                                    @endif
                                </div>
                            </div>
                        </x-slot>
                    </x-file-card>
                @endforeach
            </div>

        </div>

    </div>
</div><!-- card end -->
