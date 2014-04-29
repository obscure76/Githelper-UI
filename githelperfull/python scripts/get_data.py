import os
import MySQLdb as mdb
import json
import re
from operator import itemgetter

#clone_url, forks_count( proportional to popularity), score, stargazers_counts, open_issues_count
classmap = {'myandroiddd':'android+application', 'cloudd':'cloud+computing',
            'cnets':'computer+networks', 'data':'data+mining',
            'dsys':'distributed+systems', 'fb':'fb+application',
            'info':'information+retrieval', 'iosddd':'ios+application',
            'machinelearning':'machine+learning', 'nlppp':'natural+language+processing'}


def ranker(repolist):
    maxrepo = 150
    bestrepolist = []
    currcount = 0
    for repo in repolist:
        currcount += 1
        currrepo = {}
        currrepo['url'] = repo['clone_url']
        currrepo['forks_count'] = repo['forks_count']
        currrepo['score'] = repo['score']
        currrepo['stargazers_count'] = repo['stargazers_count']
        currrepo['open_issues_count'] = repo['open_issues_count']
        currrepo['total_score'] = repo['forks_count'] + repo['score'] + repo['stargazers_count']
        if currcount < maxrepo:
            bestrepolist.append(currrepo)
            bestrepolist = sorted(bestrepolist, key=itemgetter('total_score'))
        total_score = repo['forks_count'] + repo['score'] + repo['stargazers_count']
        lowest_repo_score = bestrepolist[-1]['total_score']
        if lowest_repo_score < total_score:
            bestrepolist.remove(bestrepolist[-1])
            bestrepolist.append(currrepo)
            bestrepolist = sorted(bestrepolist, key=itemgetter('total_score'))
    return bestrepolist

con = mdb.connect('localhost', 'root', 'sthita', 'githelper');
cur = con.cursor()
input_path="/home/sthita/spring14/db-data"
try:
    for root, subFolders, files in os.walk(input_path):
        for filename in files:
            if filename.endswith(".txt"):
                continue
            url_string=""
            total_score=""
            forks_count=""
            score=""
            stargazers_counts=""
            open_issues_count=""
            read_me=""
            print "filename::",filename
            repofile = os.path.join(root, filename)
            desc_file=os.path.join(root, filename+".txt")
            for line in open(repofile, 'r'):
                repoobj = json.loads(line)
            readme_json=json.loads(open(desc_file,'r').read())
            bestrepolist = ranker(repoobj)
            classname = filename
            for repo in bestrepolist:
                url_string=url_string+","+repo['url']
                total_score=total_score+","+str(repo['total_score'])
                forks_count=forks_count+","+str(repo['forks_count'])
                score=score+","+str(repo['score'])
                stargazers_counts=stargazers_counts+","+str(repo['stargazers_count'])
                open_issues_count=open_issues_count+","+str(repo['open_issues_count'])
                for item in readme_json:
                    if(item['url']==repo['url']):
                        temp=re.sub('[^A-Za-z0-9 ]+', "", item['description'])[0:60]+"...."
                        read_me=read_me+","+temp
                        break
            cur.execute("""
            INSERT INTO datatable (class_name,clone_url,forks_count, score,stargazers_counts,open_issues_count,total_score,readme) VALUES (%s,%s,%s,%s,%s,%s,%s,%s) """,(classname,url_string[1:], forks_count[1:],score[1:],stargazers_counts[1:],open_issues_count[1:],total_score[1:],read_me))
            print 'Succesfully Inserted the values to DB !' 
            con.commit()


except mdb.Error, e:
    print "Error %d: %s" % (e.args[0],e.args[1])
    sys.exit(1)   
finally:  
    cur.close()         
    if con:    
     con.close()
        
            
        
        
        