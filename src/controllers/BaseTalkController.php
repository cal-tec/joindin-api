<?php

class BaseTalkController extends ApiController
{
    protected function checkLoggedIn(Request $request)
    {
        $failMessages = [
            'POST' => 'create data',
            'DELETE' => 'remove data',
            'GET' => 'view data',
            'PUT' => 'update data'
        ];

        if (!isset($request->user_id)) {
            throw new Exception(
                sprintf(
                    "You must be logged in to %s",
                    $failMessages[$request->getVerb()]
                ),
                401
            );
        }
    }

    public function setTalkMapper(TalkMapper $talk_mapper)
    {
        $this->talk_mapper = $talk_mapper;
    }

    public function getTalkMapper($db, $request)
    {
        if (! isset($this->talk_mapper)) {
            $this->talk_mapper = new TalkMapper($db, $request);
        }

        return $this->talk_mapper;
    }

    public function setEventMapper(EventMapper $event_mapper)
    {
        $this->event_mapper = $event_mapper;
    }

    public function getEventMapper($db, $request)
    {
        if (! isset($this->event_mapper)) {
            $this->event_mapper = new EventMapper($db, $request);
        }

        return $this->event_mapper;
    }


    public function setUserMapper(UserMapper $user_mapper)
    {
        $this->user_mapper = $user_mapper;
    }

    public function getUserMapper($db, $request)
    {
        if (! isset($this->user_mapper)) {
            $this->user_mapper = new UserMapper($db, $request);
        }

        return $this->user_mapper;
    }

    public function setTalkCommentMapper(TalkCommentMapper $talk_comment_mapper)
    {
        $this->talk_comment_mapper = $talk_comment_mapper;
    }

    public function getTalkCommentMapper($db, $request)
    {
        if (!isset($this->talk_comment_mapper)) {
            $this->talk_comment_mapper = new TalkCommentMapper($db, $request);
        }

        return $this->talk_comment_mapper;
    }

    /**
     * Get a single talk
     *
     * @param  PDO      $db
     * @param  Request  $request
     * @param  integer  $talk_id
     * @param  boolean $verbose
     *
     * @throws Exception if the talk is not found
     *
     * @return TalkModelCollection
     */
    protected function getTalkById(
        Request $request,
        PDO $db,
        $talk_id = 0,
        $verbose = false
    ) {
        $mapper = $this->getTalkMapper($db, $request);
        if (0 === $talk_id) {
            $talk_id = $this->getItemId($request);
        }

        $talk = $mapper->getTalkById($talk_id, $verbose);
        if (false === $talk) {
            throw new Exception('Talk not found', 404);
        }

        return $talk;
    }
}
