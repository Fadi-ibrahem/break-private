from zk import ZK
import json
import shelve

def get_last_proccessed_timestamp(device_number):
    with shelve.open("./db.bin") as db:
        try:
            last_proccessed_timestamp = db[f'last_device_{device_number}_timestamp']
            return last_proccessed_timestamp
        except:
           return None


def set_last_proccessed_timestamp(device_number,timestamp):
    with shelve.open("./db.bin") as db:
        db[f'last_device_{device_number}_timestamp'] = str(timestamp)


def get_device_logs(ip):
    try:
        zk = ZK(ip, port=4370, timeout=5, password=0, force_udp=False, ommit_ping=False)
        conn = zk.connect()
        logs = conn.get_attendance()
    except Exception as e:
        logs = []
    finally:
        if 'conn' in locals():
            conn.disconnect()
        return logs

def get_unprocessed_logs(logs,device_number):
    unprocessed_logs = []
    start_index = 0
    last_proccessed_timestamp = get_last_proccessed_timestamp(device_number)
    if last_proccessed_timestamp:
        for idx,log in enumerate(logs):
            if str(log.timestamp) == last_proccessed_timestamp :
                start_index = idx + 1
                break
        
    for log in logs[start_index:]:
        unprocessed_logs.append({"user_id" : log.user_id,"timestamp" : log.timestamp})
    if len(unprocessed_logs) != 0:
        set_last_proccessed_timestamp(device_number,unprocessed_logs[-1]['timestamp'])
    return unprocessed_logs

logs_1 = get_device_logs('192.168.22.201')

logs_2 = get_device_logs('192.168.22.205')


logs = get_unprocessed_logs(logs_1, 1) + get_unprocessed_logs(logs_2, 2) 

if(len(logs) > 0):
    logs = sorted(logs, key=lambda l: l['timestamp'].timestamp()) 
    print(json.dumps(logs,default=str))

