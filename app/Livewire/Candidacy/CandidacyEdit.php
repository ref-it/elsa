<?php

namespace App\Livewire\Candidacy;

use App\Support\CandidatePicture;
use Flux\Flux;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;

#[Layout('layouts.form')]
class CandidacyEdit extends Component
{
    #[Locked]
    public int $id;

    #[Locked]
    public int $electionID;

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

    public ?int $votes;

    public bool $resigned;

    public $picture;

    public $pictureUrl;

    public $imageCropped;

    public function mount(Request $request)
    {
        $this->id = $request->id;
        $this->elecitonID = $request->election;

        $candidate = DB::table('candidates')->where('id', $this->id)->first();
        $election = DB::table('elections')->where('id', $candidate->election)->first();

        $now = date('Y-m-d H:i:s');
        if (! ($now > $election->candidacy_edit_begin && $now < $election->candidacy_edit_end)) {
            abort('403');
        }

        if ($candidate->email != auth()->user()->email) {
            abort('403');
        }

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
        if ($candidate->picture) {
            $this->pictureUrl = CandidatePicture::temporaryUrl($candidate->picture);
        }
    }

    protected function rules(): array
    {
        return [
            'answersDE' => 'array',
            'answersDE.*' => 'nullable|string|max:5000',
            'answersEN' => 'array',
            'answersEN.*' => 'nullable|string|max:5000',
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

        return view('livewire.candidacy.candidacy-edit', [
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
        $this->validate();

        $answers = [];
        array_push($answers, $this->answersDE);
        array_push($answers, $this->answersEN);

        DB::table('candidates')->where('id', $this->id)->update([
            'answers' => json_encode($answers),
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
