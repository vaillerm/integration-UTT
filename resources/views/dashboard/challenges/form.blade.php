<div class="box box-default">
    <form action={{  $slot }} method="post">
        <div class="form-group">
            <label for="name">Nom du défi</label>
            <input id="name" class="form-control" type="text" name="name" 
                                                              value="{{ isset($challenge)?$challenge->name:"" }}" required> 
        </div>

        <div class="form-group">
            <label for="desc">Description</label>
            <input id="desc" class="form-control" type="textarea" name="description"
                                                                  value="{{ isset($challenge)?$challenge->description:"" }}">
        </div>

        <div class="form-group">
            <label for="points">Nombre de points accordés</label>
            <input class="form-control" type="number" id="pts" name="points"
                                                               value={{isset($challenge)?$challenge->points:""}}>
        </div>

        <div class="form-group">
            <label for="deadline">Date limite pour valider le défi</label>
            <input id="deadline" class="form-control" type="date" name="deadline"
                                                                  value={{isset($challenge)?$challenge->deadline:""}}>
        </div>

        <div class="form-group">
            <label for=""> <input type="checkbox" name="for_newcomer" value={{ true }}
                                                  {{isset($challenge)?$challenge->for_newcomer?"checked":"":""}}> Réalisable en solo</label>
        </div>

        <input type="submit" class="btn btn-primary form-control" value="{{isset($challenge)?"Modifier un défis !":"Ajouter un défis"}}">
    </form></div>

