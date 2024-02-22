DROP TABLE Approvals;
CREATE TABLE Approvals (ID int NOT NULL AUTO_INCREMENT, MemoID int NOT NULL, UserID varchar(300), LevelApproval varchar(300), Status varchar(300), Comments mediumtext, DateCreated datetime, DateUpdated datetime, PRIMARY KEY (ID)) ENGINE=InnoDB DEFAULT CHARSET=latin1 DEFAULT COLLATE=latin1_swedish_ci;
DROP TABLE Department;
CREATE TABLE Department (ID int NOT NULL AUTO_INCREMENT, DepartmentName varchar(300) NOT NULL, DateCreated datetime, Status varchar(30) NOT NULL, DepartmentCode varchar(30) NOT NULL, DepartmentHead varchar(300), PRIMARY KEY (ID), CONSTRAINT DepartmentName UNIQUE (DepartmentName)) ENGINE=InnoDB DEFAULT CHARSET=latin1 DEFAULT COLLATE=latin1_swedish_ci;
DROP TABLE Invoice;
CREATE TABLE Invoice (ID int NOT NULL AUTO_INCREMENT, MemoID int NOT NULL, File varchar(300) NOT NULL, Status varchar(300) NOT NULL, DateCreated datetime, DateUpdated datetime, PRIMARY KEY (ID)) ENGINE=InnoDB DEFAULT CHARSET=latin1 DEFAULT COLLATE=latin1_swedish_ci;
DROP TABLE Logs;
CREATE TABLE Logs (ID int NOT NULL AUTO_INCREMENT, UserID varchar(40), LogDate timestamp DEFAULT CURRENT_TIMESTAMP NULL, Activity varchar(1000), ipAddress varchar(1000), PRIMARY KEY (ID), INDEX UserID (UserID)) ENGINE=InnoDB DEFAULT CHARSET=latin1 DEFAULT COLLATE=latin1_swedish_ci;
DROP TABLE Memo;
CREATE TABLE Memo (ID int NOT NULL AUTO_INCREMENT, Department varchar(300) NOT NULL, MemoCode varchar(300) NOT NULL, FormatID int NOT NULL, DepartmentCode varchar(300) NOT NULL, MemoDate date, SerialNumber int NOT NULL, Owner varchar(300), Position varchar(300), Through varchar(300), Recipient varchar(300), Subject varchar(300), Content longtext, Status int NOT NULL, DateCreated datetime, DateUpdated datetime, PRIMARY KEY (ID)) ENGINE=InnoDB DEFAULT CHARSET=latin1 DEFAULT COLLATE=latin1_swedish_ci;
DROP TABLE MemoFormat;
CREATE TABLE MemoFormat (ID int NOT NULL AUTO_INCREMENT, Format varchar(300) NOT NULL, Preview varchar(300) NOT NULL, Thumbnail varchar(300) NOT NULL, Through varchar(300) NOT NULL, DateCreated datetime, PRIMARY KEY (ID)) ENGINE=InnoDB DEFAULT CHARSET=latin1 DEFAULT COLLATE=latin1_swedish_ci;
DROP TABLE Signature;
CREATE TABLE Signature (ID int NOT NULL AUTO_INCREMENT, UserID varchar(30) NOT NULL, Signature varchar(300) NOT NULL, Status varchar(30) NOT NULL, DateCreated datetime, DateUpdated datetime, PRIMARY KEY (ID)) ENGINE=InnoDB DEFAULT CHARSET=latin1 DEFAULT COLLATE=latin1_swedish_ci;
DROP TABLE Users;
CREATE TABLE Users (ID int NOT NULL AUTO_INCREMENT, Surname varchar(200), Firstname varchar(200), UserID varchar(200) NOT NULL, Title varchar(200), Email varchar(200), Password varchar(255), Department varchar(200), Status enum('Active','Inactive','Deleted') DEFAULT 'Active' NOT NULL, DateCreated timestamp DEFAULT CURRENT_TIMESTAMP NULL, PRIMARY KEY (ID), CONSTRAINT UserID UNIQUE (UserID), INDEX Surname (Surname)) ENGINE=InnoDB DEFAULT CHARSET=latin1 DEFAULT COLLATE=latin1_swedish_ci;
DROP TRIGGER SendMailApprovals;
--/
CREATE TRIGGER SendMailApprovals
BEFORE INSERT ON 
dbMemoPortal.Approvals
FOR EACH ROW Begin

SET @owner = Null;
SET @email = Null;
SET @firstname = Null;
SET @format = Null;
SET @through = Null;


SELECT (SELECT CONCAT(Surname, ' ', Firstname) FROM Users WHERE UserID = Owner limit 1), ifnull((SELECT CONCAT(Surname, ' ', Firstname) FROM Users WHERE UserID = Through limit 1),(SELECT Department FROM Users WHERE UserID = Owner limit 1)) INTO @owner, @through FROM Memo WHERE ID = New.MemoID;
SELECT Format INTO @format FROM MemoFormat WHERE ID = (SELECT FormatID FROM Memo WHERE ID = New.MemoID limit 1);
SELECT Firstname, Email INTO @firstname, @email FROM Users WHERE UserID = New.UserID LIMIT 1;

IF (New.LevelApproval = 1)
THEN

INSERT INTO dbCredits.Active_Email_Mast_Memo (Email,Subject,Content) VALUES
(@email, 'MEMO APPROVAL REQUEST', concat('<p class="MsoNormal"><span style="font-size: 12pt; line-height: 107%; font-family: "andale mono", monospace;">Dear ',@firstname,',</span></p>
<p class="MsoNormal"><span style="font-size: 12pt; line-height: 107%; font-family: "andale mono", monospace;">Kindly note that ',@owner,' has initiated a ',@format,' Memo through ',@through,'.</span></p>
<p class="MsoNormal"><span style="font-size: 12pt; line-height: 107%; font-family: "andale mono", monospace;">
Please take your time to review the memo at your earliest convenience. If you have any additional comments or revisions, 
feel free to communicate them on the portal. Click <span style="color: #0070c0;"><a href="https://online.pagefinancials.com/memoportal/auth/login.html">here</a></span> to make your decision.</span></p>'));

END IF;
END
/
DROP TRIGGER SendMailCompletion;
--/
CREATE TRIGGER SendMailCompletion
AFTER UPDATE ON 
dbMemoPortal.Approvals
FOR EACH ROW Begin

SET @owneremail = NULL;
SET @ownerfirstname = Null;
SET @email = Null;
SET @firstname = Null;
SET @format = Null;
SET @percentage = Null;
SET @through = Null;
SET @owner = Null;
SET @approvalfirstname = Null;
SET @approvalemail = Null;

SELECT (SELECT CONCAT(Surname, ' ', Firstname) FROM Users WHERE UserID = Owner limit 1), ifnull((SELECT CONCAT(Surname, ' ', Firstname) FROM Users WHERE UserID = Through limit 1),(SELECT Department FROM Users WHERE UserID = Owner limit 1)) INTO @owner, @through FROM Memo WHERE ID = New.MemoID;
SELECT Format INTO @format FROM MemoFormat WHERE ID = (SELECT FormatID FROM Memo WHERE ID = New.MemoID limit 1);
SELECT Firstname, Email INTO @firstname, @email from Users where UserID = New.UserID;
SELECT (SELECT Email FROM Users WHERE UserID = Owner), (SELECT Firstname FROM Users WHERE UserID = Owner) INTO @owneremail, @ownerfirstname FROM Memo WHERE ID = New.MemoID;
SELECT ROUND((SELECT ((SELECT COUNT(Status) FROM Approvals WHERE Status = 1 AND MemoID=a.ID) / (COUNT(Status)) * 100) from Approvals where MemoID = a.ID group by MemoID)) INTO @percentage FROM Memo a WHERE ID = New.MemoID;
SELECT Firstname, Email INTO @approvalfirstname, @approvalemail FROM Approvals a INNER JOIN Users b ON a.UserID = b.UserID WHERE a.Status = 0 AND MemoID = New.MemoID LIMIT 1;


IF (New.Status='1' AND Old.Status='0')
THEN

IF (@Firstname != Null)
THEN

INSERT INTO dbCredits.Active_Email_Mast_Memo (Email,Subject,Content) VALUES
(@approvalemail, 'MEMO APPROVAL REQUEST', concat('<p class="MsoNormal"><span style="font-size: 12pt; line-height: 107%; font-family: "andale mono", monospace;">Dear ',@approvalfirstname,',</span></p>
<p class="MsoNormal"><span style="font-size: 12pt; line-height: 107%; font-family: "andale mono", monospace;">Kindly note that ',@owner,' has initiated a ',@format,' Memo through ',@through,'.</span></p>
<p class="MsoNormal"><span style="font-size: 12pt; line-height: 107%; font-family: "andale mono", monospace;">
Please take your time to review the memo at your earliest convenience. If you have any additional comments or revisions, 
feel free to communicate them on the portal. Click <span style="color: #0070c0;"><a href="https://online.pagefinancials.com/memoportal/auth/login.html">here</a></span> to make your decision.</span></p>'));
END IF;

INSERT INTO dbCredits.Active_Email_Mast_Memo (Email,Subject,Content) VALUES
(@owneremail, 'APPROVED MEMO', concat('<p class="MsoNormal"><span style="font-size: 12pt; line-height: 107%; font-family: "andale mono", monospace;">Dear ',@ownerfirstname,',</span></p>
<p class="MsoNormal"><span style="font-size: 12pt; line-height: 107%; font-family: "andale mono", monospace;">This is to inform you that your memo approval request has been reviewed and 
<strong><span style="color: #4472c4; mso-themecolor: accent1;">APPROVED</span></strong> by ',@username,'. Kindly click <span style="color: #0070c0;"><a href="https://online.pagefinancials.com/memoportal/auth/login.html">here</a></span> to view to view the status of the request</span></p>'));

INSERT INTO dbCredits.Active_Email_Mast_Memo (Email,Subject,Content) VALUES
(@email, 'APPROVED MEMO', concat('<p class="MsoNormal"><span style="font-size: 12pt; line-height: 107%; font-family: "andale mono", monospace;">Dear ',@firstname,',</span></p>
<p class="MsoNormal"><span style="font-size: 12pt; line-height: 107%; font-family: "andale mono", monospace;">Kindly note that you just <strong><span style="color: #4472c4; mso-themecolor: accent1;">APPROVED</span></strong> ',@format,' memo request initiated by ',@owner,' through ',@through,'.</span></p>
'));

END IF;

IF (New.Status='2' AND Old.Status='0')
THEN

INSERT INTO dbCredits.Active_Email_Mast_Memo (Email,Subject,Content) VALUES
(@owneremail, 'DECLINED MEMO', concat('<p class="MsoNormal"><span style="font-size: 12pt; line-height: 107%; font-family: "andale mono", monospace;">Dear ',@ownerfirstname,',</span></p>
<p class="MsoNormal"><span style="font-size: 12pt; line-height: 107%; font-family: "andale mono", monospace;">
This is to inform you that your memo approval request has been reviewed and <strong><span style="line-height: 107%; color: red;">DECLINED</span></strong><span style="line-height: 107%;"></span>by ',@username,'. 
<span style="line-height: 107%;">Kindly click <span style="color: #00b0f0;"><a href="https://online.pagefinancials.com/memoportal/auth/login.html">here</a></span> to view reason for decline.</span></span></p>'));

INSERT INTO dbCredits.Active_Email_Mast_Memo (Email,Subject,Content) VALUES
(@email, 'DECLINED MEMO', concat('<p class="MsoNormal"><span style="font-size: 12pt; line-height: 107%; font-family: "andale mono", monospace;">Dear ',@firstname,',</span></p>
<p class="MsoNormal"><span style="font-size: 12pt; line-height: 107%; font-family: "andale mono", monospace;">Kindly note that you just <strong><span style="line-height: 107%; color: red;">DECLINED</span></strong>
<span style="line-height: 107%;"> </span>',@format,' memo request initiated by ',@owner,' through ',@through,'.</span></p>'));

END IF;



IF (@percentage = '100' AND @format = 'FINANCE')
THEN

UPDATE Memo SET Status = 1, DateUpdated = now() WHERE ID = New.MemoID;

INSERT INTO dbCredits.Active_Email_Mast_Memo (Email,Subject,Content) VALUES
(@owneremail, 'COMPLETION EMAIL (PAYMENT)', concat('<p class="MsoNormal"><span style="font-size: 12pt; line-height: 107%; font-family: "andale mono", monospace;">Dear ',@ownerfirstname,',</span></p>
<p class="MsoNormal"><span style="font-size: 12pt; line-height: 107%; font-family: "andale mono", monospace;">This is to inform you that your memo has been reviewed and approved by all approvers and has flowed to Finance to carry out the payment as approved. 
Kindly click <span style="color: #00b0f0;"><a href="https://online.pagefinancials.com/memoportal/auth/login.html">here</a></span> to view or download the memo in PDF.</span></p>'));

INSERT INTO dbCredits.Active_Email_Mast_Memo (Email,Subject,Content,CC) VALUES
('financeunit@pagefinancials.com', 'PAYMENT NOTIFICATION EMAIL', concat('<p class="MsoNormal"><span style="font-size: 12pt; line-height: 107%; font-family: "andale mono", monospace;">Dear Team,</span></p>
<p class="MsoNormal"><span style="font-size: 12pt; line-height: 107%; font-family: "andale mono", monospace;">Kindly note that you have a payment memo from ',@owner,' through ',@through,'. 
Click <span style="color: #00b0f0;"><a href="https://online.pagefinancials.com/memoportal/auth/login.html">here</a></span> to confirm payment.</span></p>'), 'ajooda@pagefinancials.com');


END IF;

IF (@percentage = '100' AND @format != 'FINANCE')
THEN

UPDATE Memo SET Status = 1, DateUpdated = now() WHERE ID = New.MemoID;

INSERT INTO dbCredits.Active_Email_Mast_Memo (Email,Subject,Content) VALUES
(@owneremail, 'COMPLETION EMAIL', concat('<p class="MsoNormal"><span style="font-size: 12pt; line-height: 107%; font-family: "andale mono", monospace;">Dear ',@ownerfirstname,',</span></p>
<p class="MsoNormal"><span style="font-size: 12pt; line-height: 107%; font-family: "andale mono", monospace;">This is to inform you that your memo has been reviewed and approved by all approvers. Kindly click <span style="color: #00b0f0;">here</span> to view or download the memo in PDF.</span></p>
'));

END IF;
END
/
DROP TRIGGER SendMailOwner;
--/
CREATE TRIGGER SendMailOwner
BEFORE INSERT ON 
dbMemoPortal.Memo
FOR EACH ROW Begin

SET @email = Null;
SET @firstname = Null;

SELECT Email, Firstname INTO @email, @firstname FROM Users WHERE UserID=New.Owner;


INSERT INTO dbCredits.Active_Email_Mast_Memo (Email,Subject,Content) VALUES 
(@email, 'MEMO INITIATED AND AWAITING APPROVAL', 
concat('<p class="MsoNormal"><span style="font-size: 12pt; line-height: 107%; font-family: "andale mono", monospace;">Dear ',@firstname,',</span></p>
<p class="MsoNormal"><span style="font-size: 12pt; line-height: 107%; font-family: "andale mono", monospace;">This is to inform you that the memo you initiated has been successfully created and is now awaiting approval.</span></p>
<p class="MsoNormal"><span style="font-size: 12pt; line-height: 107%; font-family: "andale mono", monospace;">Here are the details of the memo:</span></p>
<p class="MsoNormal"><span style="font-size: 12pt; line-height: 107%; font-family: "andale mono", monospace;">Memo Title: <strong>',New.Subject,'</strong></span></p>
<p class="MsoNormal"><span style="font-size: 12pt; line-height: 107%; font-family: "andale mono", monospace;">Date of Initiation: ',DATE_FORMAT(New.DateCreated, "%M %d, %Y"),'</span></p>
<p class="MsoNormal"><span style="font-size: 12pt; line-height: 107%; font-family: "andale mono", monospace;">It is in the approval queue awaiting review and approval.</span></p>')); 

END
/
