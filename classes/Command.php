<?php 

class Command {
    protected MyPDO $db;

    //   TODO: ADD API KEY!!
    protected int $id;
    protected string $command;
    protected int $exitCode;
    protected ?string $errorMessage;
    protected string $timestamp;

    public function __construct(MyPDO $db) {
        $this->db = $db;
    }

    public function getId(): int {
        return $this->id;
    }

    public function setCommand(string $command): void {
        $this->command = $command;
    }

    public function getCommand(): string {
        return $this->command;
    }   

    public function getExitCode(): string {
        return $this->exitCode;
    }

    public function setExitCode(string $exitCode): void {
        $this->exitCode = $exitCode;
    }

    public function getErrorMessage(): ?string {
        return $this->errorMessage;
    }    

    public function setErrorMessage(string $errorMessage = NULL): void {
        $this->errorMessage = $errorMessage;
    }    

    public function getTimestamp(): string {
        return $this->timestamp;
    }    

    public function setTimestamp(string $stamp): void {
        $this->timestamp = $stamp;
    }   

    public function find($id) {  
        $data = $this->db->run("SELECT * FROM logs WHERE id = ?", 
            [$id])->fetch();
        if ($data) {
            $this->id = $data['id'];
            $this->command = $data['command'];
            $this->exitCode = $data['exit_code'];
            $this->errorMessage = $data['error_message'];
            $this->timestamp = $data['timestamp'];
        } else {
            $this->id = NULL;
            $this->command = "";
            $this->exitCode = "";
            $this->errorMessage = "";
            $this->timestamp = "";
        }
    }

    public function save() {  
        $this->db->run("INSERT INTO logs(command, exit_code, error_message, timestamp)
            VALUES (?, ?, ?, ?)", [$this->command, $this->exitCode, $this->errorMessage,
                $this->timestamp]);
        $this->id = $this->db->lastInsertId();
    }
}
?>