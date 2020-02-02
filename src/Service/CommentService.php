<?php

namespace App\Service;

use App\Entity\Comment;
use App\Exception\CommentNotFoundException;
use App\Repository\CommentRepository;

class CommentService
{
    /**
     * @var CommentRepository
     */
    private $commentRepository;

    /**
     * @var FootballMatchService
     */
    private $footballMatchService;

    public function __construct(
        CommentRepository $commentRepository,
        FootballMatchService $footballMatchService
    ) {
        $this->commentRepository = $commentRepository;
        $this->footballMatchService = $footballMatchService;
    }

    public function createAndSaveComment(int $matchId, string $content): Comment
    {
        $footballMatch = $this->footballMatchService->findFootballMatch($matchId);
        $comment = new Comment($footballMatch, $content, new \DateTime());

        $this->commentRepository->add($comment);

        return $comment;
    }

    public function update(int $commentId, string $content): Comment
    {
        /** @var Comment $comment */
        $comment = $this->findComment($commentId);
        $comment->setContent($content);

        $this->commentRepository->update($comment);

        return $comment;
    }

    public function delete(int $commentId): void
    {
        $comment = $this->findComment($commentId);
        $this->commentRepository->delete($comment);
    }

    public function findComments(int $footballMatchId): array
    {
        $footballMatch = $this->footballMatchService->findFootballMatch($footballMatchId);

        return $this->commentRepository->findByFootballMatch($footballMatch);
    }

    public function findComment(int $commentId)
    {
        $comment = $this->commentRepository->findOneById($commentId);
        if (!$comment) {
            throw new CommentNotFoundException();
        }

        return $comment;
    }
}
