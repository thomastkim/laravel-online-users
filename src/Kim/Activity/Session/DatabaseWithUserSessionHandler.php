<?php

namespace Kim\Activity\Session;

use Illuminate\Session\DatabaseSessionHandler;

class DatabaseWithUserSessionHandler extends DatabaseSessionHandler
{
    /**
     * {@inheritdoc}
     */
    public function write($sessionId, $data)
    {
        if ($this->exists)
        {
            $this->getQuery()->where('id', $sessionId)->update([
                'payload' => base64_encode($data), 'last_activity' => time(), 'user_id' => auth()->id()
            ]);
        }
        else
        {
            $this->getQuery()->insert([
                'id' => $sessionId, 'payload' => base64_encode($data), 'last_activity' => time(), 'user_id' => auth()->id()
            ]);
        }

        $this->exists = true;
    }
}
