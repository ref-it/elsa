<?php

namespace App\Livewire\Admin\Candidates;

use App\Support\CandidatePicture;
use Flux\Flux;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;

#[Layout('layouts.app')]
class CandidatesEdit extends Component
{
    #[Locked]
    public int $id;

    public string $firstname;

    public string $lastname;

    public string $email;

    public int $faculty;

    public int $course;

    public int $election;

    public int $committee;

    public ?int $list = null;

    public array $answersDE = [];

    public array $answersEN = [];

    public ?int $votes = null;

    public bool $resigned;

    public $candidacyReceived;

    public bool $approved;

    public ?string $picture;

    public string $pictureUrl = '';

    public string $imageCropped = '';

    public function mount(Request $request)
    {
        $this->id = $request->id;

        $candidate = DB::table('candidates')->where('id', $this->id)->first();
        $this->firstname = $candidate->firstname;
        $this->lastname = $candidate->lastname;
        $this->email = $candidate->email;
        $this->faculty = $candidate->faculty;
        $this->course = $candidate->course;
        $this->election = $candidate->election;
        $this->committee = $candidate->committee;
        $this->list = $candidate->list;

        $questions = DB::table('questions')
            ->select('questions')
            ->where('election', $this->election)
            ->where('committee', $this->committee)
            ->first();

        if ($candidate->answers != null) {
            $this->answersDE = json_decode($candidate->answers)[0];
            $this->answersEN = json_decode($candidate->answers)[1];
        } else {
            for ($i = 0; $i < count(json_decode($questions->questions)[0]); $i++) {
                array_push($this->answersDE, '');
                array_push($this->answersEN, '');
            }
        }

        $this->votes = $candidate->votes;
        $this->resigned = $candidate->resigned;
        $this->candidacyReceived = $candidate->candidacy_received;
        $this->approved = $candidate->approved;
        if ($candidate->picture) {
            $this->pictureUrl = CandidatePicture::temporaryUrl($candidate->picture);
        }
    }

    protected function rules(): array
    {
        return [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'faculty' => 'required|integer|exists:faculties,id',
            'course' => 'required|integer|exists:courses,id',
            'election' => 'required|integer|exists:elections,id',
            'committee' => 'required|integer|exists:committees,id',
            'list' => 'nullable|integer|exists:lists,id',
            'answersDE' => 'array',
            'answersDE.*' => 'nullable|string',
            'answersEN' => 'array',
            'answersEN.*' => 'nullable|string',
            'votes' => 'nullable|integer|min:0',
            'resigned' => 'boolean',
            'candidacyReceived' => 'nullable|date',
            'approved' => 'boolean',
        ];
    }

    public function render()
    {
        $faculties = DB::table('faculties')->get();
        $courses = DB::table('courses')->get();
        $elections = DB::table('elections')->get();
        $committees = DB::table('committees')->get();
        $lists = DB::table('lists')
            ->where('election', $this->election)
            ->where('committee', $this->committee)
            ->get();

        $questions = DB::table('questions')
            ->select('questions')
            ->where('election', $this->election)
            ->where('committee', $this->committee)
            ->first();

        return view('livewire.admin.candidates.candidates-edit', [
            'faculties' => $faculties,
            'courses' => $courses,
            'elections' => $elections,
            'committees' => $committees,
            'lists' => $lists,
            'questions' => json_decode($questions->questions),
            'pictureUrl' => $this->pictureUrl,
        ]);
    }

    public function save()
    {
        if ($this->candidacyReceived == '') {
            $this->candidacyReceived = null;
        }

        $this->validate();

        $answers = [];
        array_push($answers, $this->answersDE);
        array_push($answers, $this->answersEN);

        DB::table('candidates')->where('id', $this->id)->update([
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'email' => $this->email,
            'faculty' => $this->faculty,
            'course' => $this->course,
            'election' => $this->election,
            'committee' => $this->committee,
            'list' => $this->list,
            'answers' => json_encode($answers),
            'votes' => $this->votes,
            'resigned' => $this->resigned,
            'candidacy_received' => $this->candidacyReceived,
            'approved' => $this->approved,
        ]);

        Flux::toast(variant: 'success', text: __('admin.updated'));
    }

    public function saveImage()
    {
        $picture = CandidatePicture::store($this->imageCropped);

        if (! $picture) {
            Flux::toast(variant: 'danger', text: __('admin.invalidImage'));

            return;
        }

        DB::table('candidates')->where('id', $this->id)->update([
            'picture' => $picture,
        ]);

        $this->pictureUrl = CandidatePicture::temporaryUrl($picture);

        Flux::toast(variant: 'success', text: __('admin.pictureAdded'));
    }

    public function removeImage()
    {
        $picture = DB::table('candidates')->where('id', $this->id)->value('picture');

        DB::table('candidates')->where('id', $this->id)->update([
            'picture' => null,
        ]);

        CandidatePicture::delete($picture);

        $this->pictureUrl = null;

        Flux::toast(variant: 'success', text: __('admin.pictureRemoved'));
    }
}
