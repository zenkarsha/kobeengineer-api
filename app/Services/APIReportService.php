<?php

namespace App\Services;

use App\Repositories\PostRepository;
use App\Repositories\PostReportLogRepository;
use App\Repositories\PostCommentRepository;
use App\Services\FacebookQuerierService;
use Auth;
use Queue;

class APIReportService extends PostService
{
    protected $postRepository;
    protected $postReportLogRepository;
    protected $postCommentRepository;
    protected $facebookQuerierService;

    public function __construct(PostRepository $postRepository, PostReportLogRepository $postReportLogRepository, PostCommentRepository $postCommentRepository, FacebookQuerierService $facebookQuerierService)
    {
        $this->postRepository = $postRepository;
        $this->postReportLogRepository = $postReportLogRepository;
        $this->postCommentRepository = $postCommentRepository;
        $this->facebookQuerierService = $facebookQuerierService;

        $this->user = Auth::user();
    }

    public function reportPost($true_id)
    {
        if ((int) $this->user->banned == 1) {
            return $this->badRequestResponse('Request deined.');
            exit;
        }

        $result = $this->postRepository->getItemByTrueId($true_id);
        if ($result->published > 0)
        {
            $post_id = $result->id;
            $user_id = $this->user->id;

            $check = $this->postReportLogRepository->checkItemExist($post_id, $user_id);
            if (!count($check))
            {
                $social_count = $this->facebookQuerierService->query($post_id);

                $this->postReportLogRepository->create([
                    'post_id' => $post_id,
                    'user_id' => $user_id,
                ]);
                $this->postRepository->increaseReport($result->id);

                $fuckoff_line = (int) ($social_count['fb_likes'] / 10);
                if ($fuckoff_line < 5) $fuckoff_line = 5;

                if ($this->postReportLogRepository->countReport($post_id) >= $fuckoff_line)
                {
                    \Log::info('User report #' . $post_id . ' fuckoff, unpublish line: ' . $fuckoff_line);

                    Queue::push('App\Jobs\Unpublisher@boot', $post_id);
                }
            }

            return $this->successResponse();
        }
        else
            return $this->badRequestResponse();
    }

    public function reportComment($comment_id)
    {
        # TODO: add comment report
    }
}
