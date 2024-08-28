<div class="d-flex justify-content-between">
    <h5 class="card-title">
        {{ get_label('discussions', 'Discussions') }} : {{ $project->title }}
    </h5>
    <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#commentModal"><button type="button"
            class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right"
            data-bs-original-title="{{ get_label('add_comment', 'Add Comment') }}"><i
                class="bx bx-plus"></i></button></a>
</div>
<div id="comment-thread-container">
    <div class="comment-thread">
        @foreach ($project->comments->whereNull('parent_id')->reverse()->take(5) as $comment)
            <details open class="comment" id="comment-{{ $comment->id }}">
                <a href="#comment-{{ $comment->id }}" class="comment-border-link">
                    <span class="sr-only">Jump to comment-{{ $comment->id }}</span>
                </a>
                <summary>
                    <div class="comment-heading">

                        <div class="comment-avatar">
                            <img src="{{ $comment->user->photo ? asset('storage/' . $comment->user->photo) : asset('storage/photos/no-image.jpg') }}"
                                class="bg-footer-theme rounded-circle border"
                                alt="{{ $comment->user->first_name }} {{ $comment->user->last_name }}">
                        </div>

                        <div class="comment-info">
                            <a href="{{ route('users.show', [$comment->user->id]) }}"
                                class="comment-author fw-semibold text-body">{{ $comment->user->first_name }}
                                {{ $comment->user->last_name }}</a>
                            <p class="m-0">{{ $comment->created_at->diffForHumans() }}
                                                @if ($comment->created_at != $comment->updated_at)
                                                    <span class="text-muted">({{ get_label('edited', 'Edited') }})</span>
                                                @endif</p>
                        </div>
                        @if(isAdminOrHasAllDataAccess())

                        <div class="comment-actions d-flex ms-5 p-0">
                            <a href="javascript:void(0);" data-comment-id="{{ $comment->id }}"
                                class="btn btn-sm text-primary edit-comment p-0" data-bs-toggle="tooltip"
                                data-bs-placement="top" title="{{ get_label('edit', 'Edit') }}">
                                <i class="bx bx-edit"></i>
                            </a>
                            <a href="javascript:void(0);" data-comment-id="{{ $comment->id }}"
                                class="btn btn-sm text-danger delete-comment p-0" data-bs-toggle="tooltip"
                                data-bs-placement="top" title="{{ get_label('delete', 'Delete') }}">
                                <i class="bx bx-trash"></i>
                            </a>
                        </div>
                        @endif


                    </div>
                </summary>
                <div class="comment-body">
                    <p>{{ $comment->content }}</p>

                    <!-- Attachments Section -->
                    @if ($comment->attachments && $comment->attachments->isNotEmpty())
                        <div class="attachments mt-2">
                            @foreach ($comment->attachments as $attachment)
                                <div class="attachment-item d-flex align-items-center justify-content-between">
                                    <div class="attachment-preview-container">
                                        <a href="{{ asset('storage/' . $attachment->file_path) }}" target="_blank"
                                            class="attachment-link"
                                            data-preview-url="{{ asset('storage/' . $attachment->file_path) }}">
                                            {{ $attachment->file_name }}
                                        </a>
                                        <div class="attachment-preview"></div>
                                    </div>
                                    <a class="btn btn-sm btn-outline-dark mb-2"
                                        href="{{ asset('storage/' . $attachment->file_path) }}"
                                        download="{{ $attachment->file_name }}" class="download-button">
                                        {{ get_label('download', 'Download') }}
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <button type="button" class="open-reply-modal mt-3"
                        data-comment-id="{{ $comment->id }}">Reply</button>
                </div>
                @if ($comment->children->count() > 0)
                    <div class="replies">
                        @foreach ($comment->children->reverse() as $reply)
                            <details open class="comment" id="comment-{{ $reply->id }}">
                                <a href="#comment-{{ $reply->id }}" class="comment-border-link">
                                    <span class="sr-only">Jump to comment-{{ $reply->id }}</span>
                                </a>
                                <summary>
                                    <div class="comment-heading">
                                        <div class="comment-avatar">
                                            <img src="{{ $reply->user->photo ? asset('storage/' . $reply->user->photo) : asset('storage/photos/no-image.jpg') }}"
                                                class="bg-footer-theme rounded-circle border"
                                                alt="{{ $reply->user->first_name }} {{ $reply->user->last_name }}">
                                        </div>
                                        <div class="comment-info">
                                            <a href="{{ route('users.show', [$reply->user->id]) }}"
                                                class="comment-author text-body fw-light">{{ $reply->user->first_name }}
                                                {{ $reply->user->last_name }}</a>
                                            <p class="m-0">
                                                {{ $reply->created_at->diffForHumans() }}
                                                @if ($reply->created_at != $reply->updated_at)
                                                   <span class="text-muted">({{ get_label('edited', 'Edited') }})</span>
                                                @endif
                                            </p>

                                        </div>
                                        @if (isAdminOrHasAllDataAccess())

                                        <div class="comment-actions d-flex ms-5 p-0">
                                            <a href="javascript:void(0);" data-comment-id="{{ $reply->id }}"
                                                class="btn btn-sm text-primary edit-comment p-0"
                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="{{ get_label('edit', 'Edit') }}">
                                                <i class="bx bx-edit"></i>
                                            </a>
                                            <a href="javascript:void(0);" data-comment-id="{{ $reply->id }}"
                                                class="btn btn-sm text-danger delete-comment p-0"
                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="{{ get_label('delete', 'Delete') }}">
                                                <i class="bx bx-trash"></i>
                                            </a>
                                        </div>
                                        @endif
                                    </div>
                                </summary>
                                <div class="comment-body">
                                    <p class="text-secondary">{{ $reply->content }}</p>

                                    <!-- Attachments Section -->
                                    @if ($reply->attachments && $reply->attachments->isNotEmpty())
                                        <div class="attachments mt-2">
                                            @foreach ($reply->attachments as $attachment)
                                                <div
                                                    class="attachment-item d-flex align-items-center justify-content-between">
                                                    <div class="attachment-preview-container">
                                                        <a href="{{ asset('storage/' . $attachment->file_path) }}"
                                                            target="_blank" class="attachment-link"
                                                            data-preview-url="{{ asset('storage/' . $attachment->file_path) }}">
                                                            {{ $attachment->file_name }}
                                                        </a>
                                                        <div class="attachment-preview"></div>
                                                    </div>
                                                    <a class="btn btn-sm btn-outline-dark mb-2"
                                                        href="{{ asset('storage/' . $attachment->file_path) }}"
                                                        download="{{ $attachment->file_name }}"
                                                        class="download-button">
                                                        {{ get_label('download', 'Download') }}
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                </div>
                            </details>
                        @endforeach
                    </div>
                @endif
            </details>
        @endforeach
    </div>
    @if ($project->comments->count() > 5)
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="text-center">
                <button id="load-more-comments" class="btn btn-link text-body">
                    <i class="bx bx-chevron-down"></i>
                    {{ get_label('load_more', 'Load More') }}
                </button>
                <button id="hide-comments" class="btn btn-link text-body" style="display:none;">
                    <i class="bx bx-chevron-up"></i>
                    {{ get_label('hide', 'Hide') }}
                </button>
            </div>
        </div>
    @endif
</div>
