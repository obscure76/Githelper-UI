import sys
sys.path.append("/var/www/IR/GIT/githelper")
import parsetrain



path=sys.argv[1]
#print "!!!!!!"
contents=open(path,'r').read()

#print contents
#contents="this is to test the classifier and check if it is wotking or not for machine learning and operating system"
parsetrain.myclassify(contents)
