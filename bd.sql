#------------------------------------------------------------
#        Script MySQL.
#------------------------------------------------------------


#------------------------------------------------------------
# Table: Item
#------------------------------------------------------------

CREATE TABLE Item(
        ID      int (11) AUTO_INCREMENT  NOT NULL ,
        Name    Varchar (64) NOT NULL ,
        ImgPath Varchar (255) ,
        Brief   Text ,
        PRIMARY KEY (ID ) ,
        UNIQUE (Name )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: Stat
#------------------------------------------------------------

CREATE TABLE Stat(
        ID    int (11) AUTO_INCREMENT  NOT NULL ,
        Name  Varchar (64) NOT NULL ,
        Value Float NOT NULL ,
        PRIMARY KEY (ID ) ,
        UNIQUE (Name )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: Player
#------------------------------------------------------------

CREATE TABLE Player(
        ID      int (11) AUTO_INCREMENT  NOT NULL ,
        ImgPath Varchar (255) NOT NULL ,
        Login   Varchar (64) NOT NULL ,
        Pwd     Varchar (128) NOT NULL ,
        Pseudo  Varchar (64) NOT NULL ,
        Mail    Varchar (128) ,
        IDCurrentStep Int NOT NULL ,
        PRIMARY KEY (ID ) ,
        UNIQUE (Login )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: Admin
#------------------------------------------------------------

CREATE TABLE Admin(
        ID    int (11) AUTO_INCREMENT  NOT NULL ,
        Login Varchar (64) NOT NULL ,
        Pwd   Varchar (128) NOT NULL ,
        mail  Varchar (128) NOT NULL ,
        PRIMARY KEY (ID ) ,
        UNIQUE (Login )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: Step
#------------------------------------------------------------

CREATE TABLE Step(
        ID          int (11) AUTO_INCREMENT  NOT NULL ,
        ImgPath     Varchar (255) NOT NULL ,
        Body        Text NOT NULL ,
        Question    Text NOT NULL ,
        Accepted    Bool NOT NULL ,
        IDType Int NOT NULL ,
        PRIMARY KEY (ID )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: Achievement
#------------------------------------------------------------

CREATE TABLE Achievement(
        ID      int (11) AUTO_INCREMENT  NOT NULL ,
        Name    Varchar (64) NOT NULL ,
        ImgPath Varchar (255) NOT NULL ,
        Brief   Text NOT NULL ,
        PRIMARY KEY (ID ) ,
        UNIQUE (Name )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: StepType
#------------------------------------------------------------

CREATE TABLE StepType(
        ID   int (11) AUTO_INCREMENT  NOT NULL ,
        Name Varchar (64) NOT NULL ,
        PRIMARY KEY (ID ) ,
        UNIQUE (Name )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: PlayerAchievement
#------------------------------------------------------------

CREATE TABLE PlayerAchievement(
        IDPlayer            Int NOT NULL ,
        IDAchievement Int NOT NULL ,
        PRIMARY KEY (IDPlayer ,IDAchievement )
)ENGINE=InnoDB;

#------------------------------------------------------------
# Table: Inventory
#------------------------------------------------------------

CREATE TABLE Inventory(
        IDPlayer      Int NOT NULL ,
        IDItem Int NOT NULL ,
        PRIMARY KEY (IDPlayer ,IDItem )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: PlayerStat
#------------------------------------------------------------

CREATE TABLE PlayerStat(
        IDPlayer	Int NOT NULL ,
        IDStat 	Int NOT NULL ,
        PRIMARY KEY (IDPlayer ,IDStat )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: Approvment
#------------------------------------------------------------

CREATE TABLE Approvment(
        ApprovmentDate	Date ,
        IDAdmin     		Int NOT NULL ,
        IDStep      		Int NOT NULL ,
        PRIMARY KEY (IDAdmin ,IDStep )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: AdminWriting
#------------------------------------------------------------

CREATE TABLE AdminWriting(
        WritingDate Date ,
        IDWriter          Int NOT NULL ,
        IDStep     Int NOT NULL ,
        PRIMARY KEY (IDWriter ,IDStep )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: PastStep
#------------------------------------------------------------

CREATE TABLE PastStep(
        EndDate Date ,
        IDPlayer      Int NOT NULL ,
        IDStep Int NOT NULL ,
        PRIMARY KEY (IDPlayer ,IDStep )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: PlayerWriting
#------------------------------------------------------------

CREATE TABLE PlayerWriting(
        WritingDate Date NOT NULL ,
        IDWriter          Int NOT NULL ,
        IDStep     Int NOT NULL ,
        PRIMARY KEY (IDWriter ,IDStep )
)ENGINE=InnoDB;

#------------------------------------------------------------
# Table: Choice
#------------------------------------------------------------

CREATE TABLE Choice(
        ID    Int NOT NULL ,
        Answer Varchar (255) NOT NULL,
        IDStep Int NOT NULL,
        PRIMARY KEY (ID)
)ENGINE=InnoDB;

#------------------------------------------------------------
# Table: Transition
#------------------------------------------------------------

CREATE TABLE Transition(
        TransitionText Text ,
        IDChoice          Int NOT NULL ,
        IDNext        Int NOT NULL ,
        PRIMARY KEY (IDChoice ,IDNext )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: ItemsRequirement
#------------------------------------------------------------

CREATE TABLE ItemsRequirement(
        IDItem        Int NOT NULL ,
        IDChoice Int NOT NULL ,
        PRIMARY KEY (IDItem ,IDChoice )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: Lose
#------------------------------------------------------------

CREATE TABLE Lose(
        IDItem        Int NOT NULL ,
        IDChoice Int NOT NULL ,
        PRIMARY KEY (IDItem ,IDChoice )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: Earn
#------------------------------------------------------------

CREATE TABLE Earn(
        IDItem   Int NOT NULL ,
        IDChoice Int NOT NULL ,
        PRIMARY KEY (IDItem ,IDChoice )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: StatsRequirement
#------------------------------------------------------------

CREATE TABLE StatsRequirement(
        IDChoice      Int NOT NULL ,
        IDStat Int NOT NULL ,
        PRIMARY KEY (IDChoice ,IDStat )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: Alterations
#------------------------------------------------------------

CREATE TABLE Alterations(
        IDChoice      Int NOT NULL ,
        IDStat Int NOT NULL ,
        PRIMARY KEY (IDChoice ,IDStat )
)ENGINE=InnoDB;

ALTER TABLE Player ADD CONSTRAINT FKPlayerIDStep FOREIGN KEY (IDCurrentStep) REFERENCES Step(ID);
ALTER TABLE Step ADD CONSTRAINT FKStepIDStepType FOREIGN KEY (IDType) REFERENCES StepType(ID);
ALTER TABLE Choice ADD CONSTRAINT FKChoiceIDStep FOREIGN KEY (IDStep) REFERENCES Step(ID);
ALTER TABLE PlayerAchievement ADD CONSTRAINT FKPlayerAchievementID FOREIGN KEY (IDPlayer) REFERENCES Player(ID);
ALTER TABLE PlayerAchievement ADD CONSTRAINT FKPlayerAchievementIDAchievement FOREIGN KEY (IDAchievement) REFERENCES Achievement(ID);
ALTER TABLE Inventory ADD CONSTRAINT FKInventoryID FOREIGN KEY (IDPlayer) REFERENCES Player(ID);
ALTER TABLE Inventory ADD CONSTRAINT FKInventoryIDItem FOREIGN KEY (IDItem) REFERENCES Item(ID);
ALTER TABLE PlayerStat ADD CONSTRAINT FKStatsID FOREIGN KEY (IDPlayer) REFERENCES Player(ID);
ALTER TABLE PlayerStat ADD CONSTRAINT FKStatsIDStat FOREIGN KEY (IDStat) REFERENCES Stat(ID);
ALTER TABLE Approvment ADD CONSTRAINT FKApproverID FOREIGN KEY (IDAdmin) REFERENCES Admin(ID);
ALTER TABLE Approvment ADD CONSTRAINT FKApproverIDStep FOREIGN KEY (IDStep) REFERENCES Step(ID);
ALTER TABLE AdminWriting ADD CONSTRAINT FKAdminWritingID FOREIGN KEY (IDWriter) REFERENCES Admin(ID);
ALTER TABLE AdminWriting ADD CONSTRAINT FKAdminWritingIDStep FOREIGN KEY (IDStep) REFERENCES Step(ID);
ALTER TABLE PastStep ADD CONSTRAINT FKPastStepID FOREIGN KEY (IDPlayer) REFERENCES Player(ID);
ALTER TABLE PastStep ADD CONSTRAINT FKPastStepIDStep FOREIGN KEY (IDStep) REFERENCES Step(ID);
ALTER TABLE PlayerWriting ADD CONSTRAINT FKPlayerWritingID FOREIGN KEY (IDWriter) REFERENCES Player(ID);
ALTER TABLE PlayerWriting ADD CONSTRAINT FKPlayerWritingIDStep FOREIGN KEY (IDStep) REFERENCES Step(ID);
ALTER TABLE Transition ADD CONSTRAINT FKTransitionID FOREIGN KEY (IDChoice) REFERENCES Choice(ID);
ALTER TABLE Transition ADD CONSTRAINT FKTransitionIDStep FOREIGN KEY (IDNext) REFERENCES Step(ID);
ALTER TABLE ItemsRequirement ADD CONSTRAINT FKItemsRequirementsID FOREIGN KEY (IDItem) REFERENCES Item(ID);
ALTER TABLE ItemsRequirement ADD CONSTRAINT FKItemsRequirementsIDChoice FOREIGN KEY (IDChoice) REFERENCES Choice(ID);
ALTER TABLE Lose ADD CONSTRAINT FKLoseID FOREIGN KEY (IDItem) REFERENCES Item(ID);
ALTER TABLE Lose ADD CONSTRAINT FKLoseIDChoice FOREIGN KEY (IDChoice) REFERENCES Choice(ID);
ALTER TABLE Earn ADD CONSTRAINT FKEarnID FOREIGN KEY (IDItem) REFERENCES Item(ID);
ALTER TABLE Earn ADD CONSTRAINT FKEarnIDChoice FOREIGN KEY (IDChoice) REFERENCES Choice(ID);
ALTER TABLE StatsRequirement ADD CONSTRAINT FKStatsRequirementsID FOREIGN KEY (IDChoice) REFERENCES Choice(ID);
ALTER TABLE StatsRequirement ADD CONSTRAINT FKStatsRequirementsIDStat FOREIGN KEY (IDStat) REFERENCES Stat(ID);
ALTER TABLE Alterations ADD CONSTRAINT FKAlterationsID FOREIGN KEY (IDChoice) REFERENCES Choice(ID);
ALTER TABLE Alterations ADD CONSTRAINT FKAlterationsIDStat FOREIGN KEY (IDStat) REFERENCES Stat(ID);
