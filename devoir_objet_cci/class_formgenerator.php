<?php
require_once 'interface_form.php';
class FormGenerator implements FormGeneratorInterface {
    // je declare mes attribut de l'objet
    private string $action;
    private string $method;
    private array $erreur;
    private string $html;
    
    // le crée le construct qui crée l'instance le l'objet avec comme argument l'action du formulaire et la methode
    public function __construct($action,$method)
    {
        $this->action = $action;
        $this->method = $method;
        //j'initialise le debut mon formulaire
        $this->html = "<form action={$this->action} method={$this->method} enctype='multipart/form-data'>";
        
        

    }
    // GETTER et SETTER pour les attribue qui passe dans le constucteur 
    public function getAction()
    {
        return $this->action;
    }
    public function getMethod()
    {
        return $this->method;
    }
    public function getHTML(){
        return $this->html;
    }

    public function setAction($newAction)
    {
        $this->action = $newAction;
    }

    public function setMethod($newMethod)
    {
        $this->method = $newMethod;
    }
    
    public function addField(string $name, string $type, string $label, array $attributes = []): void
    {
        //je commence a écrire mon html
        $this->html .="<div>";
        $this->html .= " <label for={$label} > Votre {$name} </label> ";
        //si le type est egale a select alors j'effectue le code suivant
        if($type == 'select'){
            $this->html.="<select name={$name}> ";
            $this->html.="<option value={$attributes['options'][0]}>Question</option> "; 
            $this->html.="<option value={$attributes['options'][1]}>Probeme technique</option> "; 
            $this->html.="<option value={$attributes['options'][2]}>Autre</option> "; 
            $this->html.="<select>";
        }
        //sinonsi le type est egale a textarea alors j'effectue le cde suivant
        elseif($type == 'textarea')
        {
            $this->html.= "<textarea class={$attributes['class']} id={$attributes['id']}></textarea>";
        }
        elseif($type == 'radio')
        {
            $this->html .= "<input type={$type} id={$attributes['id']} name={$name} value={$attributes['value']} class={$attributes['class']}>";
        }
        elseif($type == "chexbox")
        {
            $this->html .= "<input type={$type} id={$attributes['id']} name={$name} class={$attributes['class']}>";
        }
        // sinon je fait comme ceci
        else {
        $this->html.= " <input type={$type} name={$name} class={$attributes['class']} id={$attributes['id']} {$attributes['required']}>";
        }
        $this->html .="</div>";
        $this->html .="<br>";
    //    echo $this->html;
    }

    public function render(): void
    {
        //j'affiche le reste de mon formulaire avec le bouton et la balise fermente /form
      $this->html.= "<button type=submit name=envoyer>Envoyer</button>";
      $this->html.= "</form>";
      echo $this->html;
    }

    public function handleSubmission(): bool
    {
        // si la  methode  du formulaire est en POST alors...
        if($this->method === "POST"){
            //je verifie si l'email rentrer est valide avec filter_var
            if(filter_var($_POST['email'],FILTER_VALIDATE_EMAIL))
            {
                //je compte le nombre de caractère de name et email
                $name = strlen($_POST['name']);
                $email = strlen($_POST['email']);
                // si le nombre de caractère est supperieur a 10 alors j'effectue le code suivant
                if($name > 10 && $email > 10 ) {
                    //je verifie qu'un fichier est charger
                    if(is_uploaded_file($_FILES['file_upload']['tmp_name']))
                    {
                        //je verifier que le fichier est inferrieur a 2MO
                        if($_FILES['file_upload']['size'] < 2000000)
                        {
                            //je declare un tableau avec des extension
                            $ext = ['.JPEG','.PNG','.PDF','.jpeg','.png','.pdf'];
                            //je recherche l'extension du fichier
                            $verif_ext = strrchr($_FILES['file_upload']['name'],'.');
                            //je verifie que l'extension du fichier se trouve bien dans le tableau
                            if(in_array($verif_ext,$ext))
                            {
                                return true;

                            }
                            //je gere mes Erreur
                            else{
                                echo $this->getErrors()["erreurExt"];
                                return false;}

                        }else{
                            echo $this->getErrors()["taillefichier"];
                            return false;}

                    }else{
                        echo $this->getErrors()["aucunfichier"];
                        return false;}
                
                }else{
                    echo $this->getErrors()["errorstrlen"];
                    
                    return false;}

            }else{
                echo $this->getErrors()["errorEmail"];
                return false;}
        //si la methode du formulaire est en GET 
        }else {

            //je verifie si l'email rentrer est valide avec filter_var
            if(filter_var($_GET['email'],FILTER_VALIDATE_EMAIL))
            {
                //je compte le nombre de caractère de name et email
                $name = strlen($_POST['name']);
                $email = strlen($_POST['email']);
                // si le nombre de caractère est supperieur a 10 alors j'effectue le code suivant
                if($name > 10 && $email > 10 ) {
                    //je verifie qu'un fichier est charger
                    if(is_uploaded_file($_FILES['file_upload']['tmp_name']))
                    {
                        //je verifier que le fichier est inferrieur a 2MO
                        if($_FILES['file_upload']['size'] < 2000000)
                        {
                            //je declare un tableau avec des extension
                            $ext = ['.JPEG','.PNG','.PDF','.jpeg','.png','.pdf'];
                            //je recherche l'extension du fichier
                            $verif_ext = strrchr($_FILES['file_upload']['name'],'.');
                            //je verifie que l'extension du fichier se trouve bien dans le tableau
                            if(in_array($verif_ext,$ext))
                            {
                               
                                return true;

                            }
                            //je gere mes Erreur
                            else{
                                echo $this->getErrors()["erreurExt"];
                                return false;}

                        }else{
                            echo $this->getErrors()["taillefichier"];
                            return false;}

                    }else{
                        echo $this->getErrors()["aucunfichier"];
                        return false;}
                
                }else{
                    echo $this->getErrors()["errorstrlen"];
                    return false;}
            }else{
                echo $this->getErrors()["errorEmail"];
                return false;}
        }
    }

    public function getErrors(): array
    {
       
        //je declare mon tableau d'erreur
        return ["errorEmail" => "Erreur sur votre Email",
        "errorstrlen" => "Erreur sur le nombre de caractère requis minimum 10 requis",
        "aucunfichier" => "Aucun fichier n'a été trouvé",
        "taillefichier" => "La taille de votre fichier est trop volumineuse", 
        "erreurExt" => "Erreur avec l'extension du fichier .jpeg .png .pdf  requis"];

    }


}

?>