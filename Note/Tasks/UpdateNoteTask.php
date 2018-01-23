<?php

namespace App\Containers\Note\Tasks;

use App\Containers\Note\Data\Repositories\NoteRepository;
use App\Containers\Note\Models\Note;
use App\Ship\Exceptions\UpdateResourceFailedException;
use App\Ship\Parents\Tasks\Task;
use Carbon\Carbon;
use Exception;

class UpdateNoteTask extends Task
{

    private $repository;

    public function __construct(NoteRepository $repository)
    {
        $this->repository = $repository;
    }

    public function run(Note $object, array $data)
    {
        // check if the key exists
        if (array_key_exists('is_completed', $data)) {
            $data['completed_at'] = null;

            if ($data['is_completed'] == true) {
                $data['completed_at'] = Carbon::now();
            }
        }

        try {
            return $this->repository->update($data, $object->id);
        }
        catch (Exception $exception) {
            throw new UpdateResourceFailedException();
        }
    }
}
