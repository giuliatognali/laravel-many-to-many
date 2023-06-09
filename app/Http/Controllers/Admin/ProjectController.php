<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Technology;
use App\Models\Type;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = Project::all();
        return view('admin.projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $types = Type::all();
        $technologies = Technology::all();

        return view('admin.projects.create', compact('types', 'technologies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProjectRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProjectRequest $request)
    {
        $data = $request->validated(); //prende i dati che ho validato

        $project = new Project();
        $project->fill($data);

        $project->slug = Str::slug($data['name'], '-');

        if (isset($data['image'])) {
            $project->image = Storage::put('uploads', $data['image']);
        }

        $project->save();

        if (isset($data['technologies'])) {  //se le techn sono settate 
            $project->technologies()->sync($data['technologies']);  //da inserire dopo save perchè altrimenti non ho ancora project_id
        }
        return redirect()->route('admin.projects.index')
            ->with('message', 'Project add successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(string $slug)
    {
        $project = Project::where('slug', $slug)->first();
        //dammi tutti i progetti dove il campo slug è uguale alla stringa slug che sto ricevendo
        //ottengo il progetto specifico con get ottengo una collection
        return view('admin.projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        $types = Type::all();
        $technologies = Technology::all();
        return view('admin.projects.edit', compact('project', 'types', 'technologies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProjectRequest  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {

        $data = $request->validated();
        $project->slug = Str::slug($data['name']);

        if (empty($data['set_image'])) {   //se l'img non è settata, quindi empty
            if ($project->image) {            //se l'immagine c'era la cancello e svuoto il valore (null)
                Storage::delete($project->image);
                $project->image = null;
            }
        } else {
            if (isset($data['image'])) {
                if ($project->image) {        //se setto un immagine e se esiteva un'immagine prima allora la cancello
                    Storage::delete($project->image);
                }
                $project->image = Storage::put('uploads', $data['image']);
            }
        }
        

        $technologies = isset($data['technologies']) ? $data['technologies'] : [];  //se technologies esiste mi salvi data['technologies'] altrimenti un arrat vuoto
            $project->technologies()->sync($technologies);

        $project->update($data);

        //$project->save(); update salva in automatico

        return redirect()->route('admin.projects.index')
            ->with('message',  "Project $project->name updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        //$old_id =$project->id; //salva l'id prima di cancellarlo per poterlo inserire nel messaggio

        if ($project->image) {
            Storage::delete($project->image);
        }

        $project->delete();
        return redirect()->route('admin.projects.index')
            ->with('message',  "Project $project->name deleted successfully");
    }
}
